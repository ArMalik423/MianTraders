<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{asset('backend_view/assets/images/ht_logo.png')}}" class="logo-icon" alt="logo icon" style="height: 50px;width: 50px">
        </div>
        <div>
            <h4 class="logo-text">Hussnain Traders</h4>
        </div>
        <div class="toggle-icon ms-auto"> <i class="bi bi-list"></i>
        </div>
    </div>
    <!--navigation-->

    <ul class="metismenu" id="menu">

        <div class="menu-title">Products</div>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-box"></i>
                </div>
                <div class="menu-title">Products</div>
            </a>
            <ul>
                <li> <a href="{{route('get.viewer.products')}}"><i class="bi bi-circle"></i>All Products</a>
                </li>
            </ul>
        </li>


        <div class="menu-title">Ledgers</div>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-calculator"></i>
                </div>
                <div class="menu-title">Ledgers</div>
            </a>
            <ul>
                <li> <a href="{{route('viewer.ledgers')}}"><i class="bi bi-circle"></i>All Shops Ledger</a>
                </li>
            </ul>
        </li>

    </ul>
    <!--end navigation-->
</aside>
