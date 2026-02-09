<?php

namespace App\Services\Map;

use Illuminate\Support\Facades\DB;

class LayersService
{
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

        return [
            'groups' => $groups,
            'services' => $services,
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
}
