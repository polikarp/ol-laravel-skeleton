<?php

namespace App\Services\Map;

use Illuminate\Support\Facades\DB;

class LayersService
{


    private const BASE_LAYER_KEY = 'base_layers';

    /**
     * Build bootstrap payload: groups + services.
     *
     * @return array{groups: array<int, array<string, mixed>>, services: array<int, array<string, mixed>>}
     */
    public function build(): array
    {
        $services = DB::table('public.gis_service')
            ->where('enabled', true)
            ->orderBy('id')
            ->get(['id', 'group_id', 'name', 'type', 'base_url', 'version', 'options'])
            ->map(function ($s) {
                return [
                    'id' => (int)$s->id,
                    'group_id' => (int)$s->group_id,
                    'name' => $s->name,
                    'type' => $s->type,
                    'base_url' => $s->base_url,
                    'version' => $s->version,
                    // Ensure options is always a decoded structure
                    'options' => $this->normalizeJsonb($s->options),
                ];
            })
            ->values()
            ->all();

        $groups = DB::table('public.gis_layer_group')
            ->where('enabled', true)
            ->orderBy('order_idx')
            ->orderBy('id')
            ->get(['id', 'key', 'title', 'order_idx', 'parent_id', 'collapsed_default', 'icon'])
            ->map(function ($g) {
                return [
                    'id' => (int)$g->id,
                    'key' => $g->key,
                    'title' => $g->title,
                    'order_idx' => (int)$g->order_idx,
                    'parent_id' => $g->parent_id ? (int)$g->parent_id : null,
                    'collapsed_default' => (bool)$g->collapsed_default,
                    'icon' => $g->icon,
                ];
            })
            ->values()
            ->all();

        $layers = DB::table('public.gis_layer')
            ->where('enabled', true)
            ->orderBy('group_id')
            ->orderBy('z_index')
            ->orderBy('id')
            ->get(['id', 'group_id', 'title', 'layer_name', 'description', 'base_url', 'layer_type', 'geom_field', 'visible_default', 'z_index', 'queryable', 'options',])
            ->map(function ($l) {
                return [
                    'id' => (int) $l->id,
                    'group_id' => (int) $l->group_id,
                    'title' => $l->title,
                    'layer_name' => $l->layer_name,
                    'description' => $l->description,
                    'base_url' => $l->base_url,
                    'type' => $l->layer_type,
                    'geom_field' => $l->geom_field,
                    'visible_default' => (bool) $l->visible_default,
                    'z_index' => (int) $l->z_index,
                    'queryable' => (bool) $l->queryable,
                    'options' => is_string($l->options)
                        ? json_decode($l->options, true)
                        : (array) $l->options,
                ];
            })
            ->values()
            ->all();


        return [
            'groups' => $groups,
            'services' => $services,
            'layers' => $layers,
        ];
    }

    /**
     * Normalize a json/jsonb field coming from DB into array/object.
     *
     * @param mixed $value
     * @return mixed
     */
    private function normalizeJsonb($value)
    {
        // pgsql may return jsonb as string depending on config/driver
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return $decoded ?? (object)[];
        }

        // If it's already an array/object, return as-is
        return $value ?? (object)[];
    }

    public function getBaseLayers()
    {

        return DB::table('public.gis_layer_group as glg')
            ->join('public.gis_layer as gl', 'gl.group_id', '=', 'glg.id')
            ->where('glg.key', self::BASE_LAYER_KEY)
            ->select('gl.*')
            ->get();
    }
}
