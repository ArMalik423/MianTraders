<header class="top-header">        
      <nav class="navbar navbar-expand gap-3">
        <div class="mobile-toggle-icon fs-3">
            <i class="bi bi-list"></i>
          </div>
          <form class="searchbar">
              <div class="position-absolute top-50 translate-middle-y search-close-icon"><i class="bi bi-x-lg"></i></div>
          </form>
          <div class="top-navbar-right ms-auto">
            <ul class="navbar-nav align-items-center">
             
            <li class="nav-item dropdown dropdown-user-setting">
              <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                <div class="user-setting d-flex align-items-center">
                  <img src="{{asset('backend_view/assets/images/avatar-1.png')}}" class="user-img" alt="">
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                   <a class="dropdown-item" href="#">
                     <div class="d-flex align-items-center">
                        <img src="{{asset('backend_view/assets/images/avatar-1.png')}}" alt="" class="rounded-circle" width="54" height="54">
                        <div class="ms-3">
                          <h6 class="mb-0 dropdown-user-name">{{ auth()->user()->name ?? ''}}</h6>
                          <small class="mb-0 dropdown-user-designation text-secondary">{{ auth()->user()->email ?? '' }}</small>
                        </div>
                     </div>
                   </a>
                 </li>
                 <li><hr class="dropdown-divider"></li>
                 <li>
                    <a class="dropdown-item" href="pages-user-profile.html">
                       <div class="d-flex align-items-center">
                         <div class=""><i class="bi bi-person-fill"></i></div>
                         <div class="ms-3"><span>Change Password</span></div>
                       </div>
                     </a>
                  </li>
                  
                  <li>
                    
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                                      <i class="bi bi-lock-fill me-3"></i>
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                  </li>
                  
                    
              </ul>
            </li> 
              </div>
            </li>
            </ul>
            </div>
      </nav>
    </header>