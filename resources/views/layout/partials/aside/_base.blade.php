<!--begin::Aside-->
<div id="kt_aside" class="aside" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Aside Toolbarl-->
    <div class="aside-toolbar flex-column-auto" id="kt_aside_toolbar">
        <!--begin::Aside user-->
            @include(config('settings.THEME_LAYOUT_DIR') . '/partials/aside/user/_base')
        <!--end::Aside user-->

        <!--begin::Aside search-->

        <!--end::Aside search-->
    </div>
    <!--end::Aside Toolbarl-->
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        @include(config('settings.THEME_LAYOUT_DIR') . '/partials/aside/partials/_menu')
    </div>
    <!--end::Aside menu-->
</div>
<!--end::Aside-->
