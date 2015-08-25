<nav>

	<h3>Навигация</h3>

	<ul>
		<li><a href="{{ URL::route('home') }}">Главная</a></li>

		@if (Auth::check())
			
		@else
			<li><a href="{{ URL::route('account.create') }}">Зарегистрироваться</a></li>
		@endif

	</ul>
</nav>