 <!-- [ Header Topbar ] start -->
 <header class="pc-header">
     <div class="header-wrapper">
         <div class="me-auto pc-mob-drp">
             <ul class="list-unstyled">
                 <li class="pc-h-item header-mobile-collapse">
                     <a href="#" class="pc-head-link head-link-secondary ms-0" id="sidebar-hide">
                         <i class="ti ti-menu-2"></i>
                     </a>
                 </li>
                 <li class="pc-h-item pc-sidebar-popup">
                     <a href="#" class="pc-head-link head-link-secondary ms-0" id="mobile-collapse">
                         <i class="ti ti-menu-2"></i>
                     </a>
                 </li>
                 <li class="dropdown pc-h-item d-inline-flex d-md-none">
                     <a class="pc-head-link head-link-secondary dropdown-toggle arrow-none m-0"
                         data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                         aria-expanded="false">
                         <i class="ti ti-search"></i>
                     </a>
                     <div class="dropdown-menu pc-h-dropdown drp-search">
                         <form class="px-3">
                             <div class="mb-0 d-flex align-items-center">
                                 <i data-feather="search"></i>
                                 <input type="search" class="form-control border-0 shadow-none"
                                     placeholder="Search here. . ." />
                             </div>
                         </form>
                     </div>
                 </li>
               
             </ul>
         </div>

         <div class="ms-auto">
             <ul class="list-unstyled">
                 <li class="dropdown pc-h-item header-user-profile">
                     <a class="pc-head-link head-link-primary dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                         href="#" role="button" aria-haspopup="false" aria-expanded="false">
                         <img src="../assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar" />
                         <span>
                             <i class="ti ti-settings"></i>
                         </span>
                     </a>
                     <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                         <div class="dropdown-header">
                             <div class="d-flex align-items-center">
                                 <h3 class="me-2">Welcome</h3>
                                 <h3>{{ Auth::user()->name }}</h3>
                             </div>
                             <hr />
                             <div class="profile-notification-scroll position-relative"
                                 style="max-height: calc(100vh - 280px)">

                                 <!-- Open Modal -->
                                 <a href="#" class="dropdown-item" id="openUpdateProfileModal">
                                     <i class="ti ti-settings"></i>
                                     <span>Update Profile</span>
                                 </a>

                                 <a href="#" class="dropdown-item">
                                     <i class="ti ti-user"></i>
                                     <span>Social Profile</span>
                                 </a>
                                 <a class="dropdown-item" href="#"
                                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                     <i class="ti ti-logout"></i>
                                     Logout
                                 </a>

                                 <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                     @csrf
                                 </form>

                             </div>
                         </div>
                     </div>
                 </li>
             </ul>
         </div>
     </div>

     <!-- Modal for Update Profile -->
   



     <script>
         $(document).ready(function() {
             $("#openUpdateProfileModal").click(function() {
                 // Fill user details dynamically
                 $("#updateUserId").val("{{ auth()->user()->id }}");
                 $("#updateName").val("{{ auth()->user()->name }}");
                 $("#updateEmail").val("{{ auth()->user()->email }}");
                 $("#updateMobile").val("{{ auth()->user()->mobile ?? '' }}");

                 // Set gender
                 let gender = "{{ auth()->user()->gender ?? '' }}";
                 if (gender) {
                     $("#updateGender").val(gender);
                 }

                 // Show the modal
                 $("#updateProfileModal").modal("show");
             });
         });
     </script>
 </header>
 <!-- [ Header ] end -->
