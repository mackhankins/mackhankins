<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{!! route('/') !!}">Mack Hankins</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="main-collapse">
            <ul class="nav navbar-nav navbar-right">
                @include('partials.pub._navbar_items', ['items'=> $menu_public->roots()])
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
</nav>