@include('includes.header')

 @include('includes.sidebar')
 <!-- Main content -->

 <div id="content-wrapper" class="d-flex flex-column">
    @include('includes.topbar')
    @include('includes.message')
    <!-- Main Content -->
    <div id="content">

 @yield('content')


@include('includes.footer')

