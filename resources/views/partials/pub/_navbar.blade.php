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
                <li><a href="{{ route('/') }}">Home</a></li>
                <li><a href="{{ route('blog') }}">Blog</a></li>
                @role('admin')
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span> Admin
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ url('/logout') }}">logout</a></li>
                    </ul>
                </li>
                @endrole
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
</nav>