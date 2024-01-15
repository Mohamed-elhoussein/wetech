<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled fs-3" id="side-menu">
            
                @php
                    $paths = [];
                    foreach (menu() as $path) {
                        if (is_array(Auth::user()->permissions) && array_key_exists('policy', $path) && in_array($path['policy'], Auth::user()->permissions)) {
                            array_push($paths, $path);
                        } elseif (!array_key_exists('policy', $path)) {
                            array_push($paths, $path);
                        }
                    }

                @endphp

                @foreach ($paths as $path)
                    @if (isset($path['children']))
                        <li>
                            <a href="javascript: void(0);" class="waves-effect fs-5">
                                <i class="{{ $path['icon'] }}"></i>
                                <span key="t-dashboards"> {{ $path['title'] }}</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @foreach ($path['children'] as $child)
                                    <li><a href="{{ $child['route'] }}" key="t-default">{{ $child['title'] }}</a>
                                    </li>
                                @endforeach



                            </ul>
                        </li>
                    @else
                        <li>
                            <a href="{{ $path['route'] }}" class="waves-effect fs-5">
                                @isset($path['icon'])
                                    <i class="{{ $path['icon'] }}"></i>
                                @else
                                    <img class="icon" src="{{ $path['image'] }}" alt="">
                                @endisset
                                <span key="t-chat ">{{ $path['title'] }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach


                {{-- <li>
                    <a href="/" class="waves-effect fs-5">
                        <i class="bx bx-home"></i>
                        <span key="t-chat ">نظرة عامة</span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="waves-effect fs-5">
                        <i class="bx bx-user"></i>
                        <span key="t-dashboards"> الاعظاء</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="/user/admins" key="t-default">المشرفين</a></li>
                        <li><a href="/user/providers" key="t-saas">مزودي الخدمات</a></li>
                        <li><a href="/user/users" key="t-crypto">المستخدمين</a></li>

                    </ul>
                </li>
                 <li>
                    <a href="/pages" class="waves-effect fs-5">
                        <i class="bx bx-movie "></i>
                        <span key="t-chat ">ادارة الصفحات</span>
                    </a>
                </li>
                <li>
                <li>
                    <a href="/slider" class="waves-effect fs-5">
                        <i class="bx bx-share-alt"></i>
                        <span key="t-chat ">الشرائح</span>
                    </a>
                </li>
                <li>
                    <a href="/services" class="waves-effect fs-5">
                        <i class="bx bx-table "></i>
                        <span key="t-chat "> خدمات</span>
                    </a>
                </li>
                <li>
                    <a href="/service/category" class="waves-effect fs-5">
                        <i class="bx bx-food-menu"></i>
                        <span key="t-chat ">تصنيف الخدمات</span>
                    </a>
                </li>
                <li>
                    <a href="/service/subcategories" class="waves-effect fs-5">
                        <i class="bx bx-grid"></i>
                        <span key="t-chat ">التصنيف الفرعي للخدمات</span>
                    </a>
                </li>
                <li>
                    <a href="/providers/services" class="waves-effect fs-5">
                        <i class="bx bx-globe"></i>
                        <span key="t-chat "> خدمات المزودين </span>
                    </a>
                </li>
                {{-- <li>
                        <a href="/coupons" class="waves-effect fs-5">
                            <i class="bx bx-dollar-circle"></i>
                            <span key="t-chat "> الخصومات المالية</span>
                        </a>
                    </li>
                <li>
                    <a href="/orders" class="waves-effect fs-5">

                        <i class='bx bx-notepad'></i>
                        <span key="t-chat "> الطلبات</span>
                    </a>
                </li>
                <li>
                    <a href="/reports" class="waves-effect fs-5">
                        <i class='bx bxs-folder-open'></i>
                        <span key="t-chat "> البلاغات</span>
                    </a>
                </li>
                <li>
                    <a href="/transactions" class="waves-effect fs-5">
                        <i class="bx bx-money "></i>
                        <span key="t-chat ">التحويلات</span>
                    </a>
                </li>
                <li>
                    <a href="/faq" class="waves-effect fs-5">
                        <i class="bx bx-directions"></i>
                        <span key="t-chat ">الأسئلة الشائعة</span>
                    </a>
                </li>
                <li>
                    <a href="/settings" class="waves-effect fs-5">
                        <i class="bx bxs-cog"></i>
                        <span key="t-chat ">الاعدادات</span>
                    </a>
                </li>

                {{-- <li>
                    <a href="apps-filemanager.html" class="waves-effect">
                        <i class="bx bx-file"></i>
                        <span class="badge rounded-pill bg-success float-end" key="t-new">New</span>
                        <span key="t-file-manager">File Manager</span>
                    </a>
                </li> --}}



                {{-- <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-file"></i>
                        <span key="t-utility">Utility</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="pages-starter.html" key="t-starter-page">Starter Page</a></li>
                        <li><a href="pages-maintenance.html" key="t-maintenance">Maintenance</a></li>
                        <li><a href="pages-comingsoon.html" key="t-coming-soon">Coming Soon</a></li>
                        <li><a href="pages-timeline.html" key="t-timeline">Timeline</a></li>
                        <li><a href="pages-faqs.html" key="t-faqs">FAQs</a></li>
                        <li><a href="pages-pricing.html" key="t-pricing">Pricing</a></li>
                        <li><a href="pages-404.html" key="t-error-404">Error 404</a></li>
                        <li><a href="pages-500.html" key="t-error-500">Error 500</a></li>
                    </ul>
                </li> --}}


            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
