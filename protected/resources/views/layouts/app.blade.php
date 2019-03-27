@include('layouts/header')

        <main class="py-4">
        	@yield('jumbotron')
            @yield('content')
            @yield('sidebar')
        </main>
        </div>

@include('layouts/footer')
@include('includes.javascript_general')

@yield('javascript')
