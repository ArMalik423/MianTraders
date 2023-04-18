<aside class="sidebar-wrapper" data-simplebar="true">
          <div class="sidebar-header">
            <div>
              <img src="assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
            </div>
            <div>
              <h4 class="logo-text">Husnain Traders</h4>
            </div>
            <div class="toggle-icon ms-auto"> <i class="bi bi-list"></i>
            </div>
          </div>
          <!--navigation-->

          <ul class="metismenu" id="menu">
              <li class="menu-label">Viewers</li>
            <li>
              <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-person-fill"></i>
                </div>
                <div class="menu-title">Viewers</div>
              </a>
              <ul>
                <li> <a href="{{route('get.viewers')}}"><i class="bi bi-circle"></i>View Viewers</a>
                </li>
                <li> <a href="{{route('get.add.viewer')}}"><i class="bi bi-circle"></i>Add Viewers</a>
                </li>
              </ul>
            </li>

              <div class="menu-title">Products</div>
            <li>
              <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-plus-square-fill"></i>
                </div>
                <div class="menu-title">Products</div>
              </a>
              <ul>
                <li> <a href="{{route('get.products')}}"><i class="bi bi-circle"></i>All Products</a>
                </li>
                <li> <a href="{{route('get.add.product')}}"><i class="bi bi-circle"></i>Add Product</a>
                </li>
              </ul>
            </li>

              <div class="menu-title">Shops</div>
            <li>
              <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-plus-square-fill"></i>
                </div>
                <div class="menu-title">Shops</div>
              </a>
              <ul>
                <li> <a href="{{route('get.shops')}}"><i class="bi bi-circle"></i>All Shops</a>
                </li>
                <li> <a href="{{route('get.add.shop')}}"><i class="bi bi-circle"></i>Add Shops</a>
                </li>
              </ul>
            </li>

              <div class="menu-title">Ledgers</div>
          <li>
              <a href="javascript:;" class="has-arrow">
                  <div class="parent-icon"><i class="bi bi-plus-square-fill"></i>
                  </div>
                  <div class="menu-title">Ledgers</div>
              </a>
              <ul>
                  <li> <a href="{{route('get.ledgers')}}"><i class="bi bi-circle"></i>All Users Ledger</a>
                  </li>
                  <li> <a href="{{route('get.add.ledger')}}"><i class="bi bi-circle"></i>Add Ledger Record</a>
                  </li>
              </ul>
          </li>

          </ul>
          <!--end navigation-->
</aside>
