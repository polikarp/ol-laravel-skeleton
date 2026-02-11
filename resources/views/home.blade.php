@extends('layouts.app')

@section('content')
<div class="container m-0 p-0">
    <div id="map"  data-baseLayers='@json($baseLayers)' style="width:100%; height: calc(100vh - 104px);"></div>


    <!-- Left layers menu -->
    <div id="gis-toolbar" class="gis-toolbar d-flex flex-column align-items-center">
        <button class="toolbar-button bottom-menu-button" data-target="base-map-menu" title="@lang('messages.base_map')">
            <i class="fas fa-globe"></i>
            <span class="menu-label">@lang('messages.base')</span>
        </button>

        <div class="button-separator-vertical"></div>

        <button class="toolbar-button bottom-menu-button" data-target="wms-menu" title="WMS Layers">
            <i class="fas fa-layer-group"></i>
            <span class="menu-label">@lang('messages.layers')</span>
        </button>

        {{-- <div class="button-separator-vertical"></div> --}}
    </div>

    <!-- MENÚS FLOTANTES -->
    @php
        use Illuminate\Support\Str;

        $layer_geoserver = request('layer_geoserver', []);
        $layer_selected  = request('layer_selected');
    @endphp
    <div id="base-map-menu" class="gis-toolbar-flyout ">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6>@lang('messages.base_map')</h6>
            <div class="wms-menu-close">
                <i class="fa-solid fa-xmark closeMenu" data-target="base-map-menu" title="@lang('messages.close')" role="button"></i>
            </div>
        </div>

        <div class="row g-2">
            @foreach ($layer_geoserver as $layer)
                @php $layerName = Str::after($layer, ':'); @endphp
                <div class="col-4">
                    <img src="{{ asset('images/baselayers/' . $layerName . '.png') }}"
                        class="img-fluid rounded base-thumb"
                        data-layer="{{ $layer }}"
                        title="{{ $layer }}"
                        @if ($layer_selected === $layer) style="border: 2px solid #0d6efd;" @endif>
                </div>
            @endforeach
        </div>
    </div>

     <div id="wms-menu" class="gis-toolbar-flyout ">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="mb-0">@lang('messages.layers')</h6>

            <div class="wms-menu-close">
                <i class="fa-solid fa-xmark closeMenu" data-target="wms-menu" title="@lang('messages.close')" role="button"></i>
            </div>
        </div>
        <input
            type="text"
            id="layersSearchInput"
            class="form-control form-control-sm mb-2"
            placeholder="@lang('messages.search_layers')"
            autocomplete="off"
        />
        <div class="row g-2">
            <ul id="layersMenuSelector" class="list-unstyled">
            </ul>
        </div>
    </div>

    <div id="profileWindow" style="display:none;">
        <div class="profile-header">
            <span>Elevation Profile</span>
            <button id="closeProfile">&times;</button>
        </div>
        <canvas id="profile"></canvas>
    </div>

    <div id="mouse-coordinates" class="map-coordinates"></div>

    <div class="search-box">
        <div class="search-wrapper">
            <span class="search-icon"><i class="fas fa-search"></i></span>
            <input type="text" id="searchInput" class="form-control search-input" placeholder="@lang('messages.search')">
            <span class="search-clear d-none" id="clearSearch"><i class="fas fa-times"></i></span>
        </div>
        <ul id="autocompleteResults" class="list-group d-none"></ul>
    </div>

    <div id="gis-bottom-menu" class="gis-bottom-menu d-flex align-items-center">
        <!-- Botón ☰ solo visible en móvil -->
        <button id="toggleMenuBtn" class="d-block d-sm-none bottom-menu-toggle" title="Mostrar herramientas">
            ☰
        </button>

        <!-- Contenedor de botones originales -->
        <div id="menuButtons" class="d-flex align-items-center">
            <button id="btnMeasureLength" class="bottom-menu-button hideResponsive" title="@lang('messages.measure_distance')">
                <i class="fas fa-ruler"></i>
                <span class="menu-label">@lang('messages.ruler')</span>
            </button>
            <div class="button-separator hideResponsive"></div>
            <button id="btnMeasureArea" class="bottom-menu-button hideResponsive" title="@lang('messages.measure_area')">
                <i class="fas fa-draw-polygon"></i>
                <span class="menu-label">@lang('messages.area')</span>
            </button>
            <div class="button-separator hideResponsive"></div>
            <button id="btnProfile" class="bottom-menu-button hideResponsive" title="@lang('messages.profile')">
                <i class="fas fa-mountain"></i>
                <span class="menu-label">@lang('messages.profile')</span>
            </button>
            <div class="button-separator hideResponsive"></div>
            <button id="btnCancelMeasure" class="bottom-menu-button d-none hideResponsive" title="@lang('messages.cancel_measure')">
                <i class="fas fa-times"></i>
                <span class="menu-label">@lang('messages.cancel')</span>
            </button>
            <div class="button-separator hideResponsive d-none"></div>
            {{-- <button id="openTableBtn" class="bottom-menu-button hideResponsive" title="@lang('messages.table_mode')">
                <i class="fas fa-table"></i>
                <span class="menu-label">@lang('messages.table')</span>
            </button>
            <div class="button-separator hideResponsive"></div> --}}
            <button class="bottom-menu-button" id="gps" title="@lang('messages.gps_location')">
                <i class="fas fa-location-crosshairs"></i>
                <span class="menu-label">@lang('messages.location')</span>
            </button>
            <div class="button-separator"></div>
            <button class="bottom-menu-button" id="home" title="@lang('messages.center_map')">
                <i class="fas fa-crosshairs"></i>
                <span class="menu-label">@lang('messages.center')</span>
            </button>
            <div class="button-separator"></div>
            <button class="bottom-menu-button" id="btnSpatialViewport" title="@lang('messages.bbox')">
                <i class="fas fa-info-circle"></i>
                <span class="menu-label">@lang('messages.bbox')</span>
            </button>
            <div class="button-separator"></div>
            <button id="rotate" class="bottom-menu-button" title="@lang('messages.reset_rotation')">
                <i class="fa-solid fa-compass"></i>
                <span class="menu-label">@lang('messages.reset')</span>
            </button>
            <div class="button-separator "></div>
             <button id="rotate90" class="bottom-menu-button" title="@lang('messages.rotation_left')">
                <i class="fas fa-rotate-left"></i>
                <span class="menu-label">@lang('messages.rotate')</span>
            </button>
            <div class="button-separator hideResponsive"></div>
            <button id="btnSpatialDraw" class="bottom-menu-button hideResponsive" title="@lang('messages.spatial_search')">
                <i class="fas fa-draw-polygon"></i>
                <span class="menu-label">@lang('messages.search')</span>
            </button>
            <div class="button-separator hideResponsive"></div>
            <button id="btnSpatialDrawCancel" class="bottom-menu-button hideResponsive" title="@lang('messages.spatial_clear')">
                <i class="fas fa-times"></i>
                <span class="menu-label">@lang('messages.clear')</span>
            </button>
            {{-- <div class="button-separator hideResponsive"></div>
            <button id="btnUploadLayer" class="bottom-menu-button hideResponsive" title="@lang('messages.upload_layer')">
                <i class="fas fa-upload"></i>
                <span class="menu-label">@lang('messages.upload_layer')</span>
            </button>
            <input type="file" id="inputUploadLayer" accept=".geojson,.json,.kml,.gpx" style="display:none;"> --}}


        </div>

    </div>



    <!-- Right panel -->
    <div id="gfiRightPanel" class="gfi-panel">
        <div class="gfi-panel__header">
            <div class="gfi-panel__title">Identify results</div>
            <button id="gfiPanelClose" title="Close">✖</button>
            {{-- <button id="gfiPanelClose" type="button" class="btn btn-sm btn-outline-secondary">×</button> --}}
        </div>

        <div id="gfiLoading" class="gfi-loading d-none">
            <i class="fa fa-spinner fa-spin me-2"></i>
            <span>Loading…</span>
        </div>

        <div id="gfiPanelBody" class="gfi-panel__body">
            <div class="text-muted small">Click on the map to identify features.</div>
        </div>
    </div>

    <!-- Searching modal -->
    <div class="modal fade" id="layerFiltersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">@lang('messages.filters')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <ul class="nav nav-tabs" id="layerFiltersTabs" role="tablist"></ul>

                    <div class="tab-content pt-3" id="layerFiltersTabContent"></div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    @lang('messages.close')
                    </button>
                </div>
            </div>
        </div>
    </div>


</div>

<script>
    window.APP_VERSION = "{{ config('version.last_local_tag') }}";
    window.APP_YEAR = "{{ config('version.date_year') }}";
</script>
@endsection
