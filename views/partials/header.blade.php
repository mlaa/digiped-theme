<header class="banner">
	<div class="container cf pa2">
		<div class="fl w-50 tl">
			<a class="brand" href="{{ home_url('/') }}">{{ get_bloginfo('name', 'display') }}</a>
		</div>
		<div class="fl w-50 tr">
			<nav class="nav-primary">
				@if (has_nav_menu('primary_navigation'))
					{!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']) !!}
				@endif
			</nav>
		</div>
	</div>
</header>
