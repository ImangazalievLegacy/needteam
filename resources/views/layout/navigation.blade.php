<nav>

	<h3>Навигация</h3>

	<ul>
		<li><a href="{{ URL::route('home') }}">Главная</a></li>

		@if (Auth::check())
			<li><a href="{{ URL::route('account.logout') }}">Выйти</a></li>
		@else
			<li><a href="{{ URL::route('account.create') }}">Зарегистрироваться</a></li>
			<li><a href="{{ URL::route('account.login') }}">Войти</a></li>
		@endif

	</ul>
</nav>