@include('cms.layouts.header')
@include('cms.layouts.nav')


   

        <div class="py-4">     
            <div class="d-flex justify-content-between w-100 flex-wrap">
                <div class="mb-3 mb-lg-0">
                    <h1 class="h4">{{ $title }}</h1>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                @yield('content')
            </div>
        </div>
       

@include('cms.layouts.footer')