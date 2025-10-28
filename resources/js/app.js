import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
	Alpine.data('themeToggle', () => ({
		isDark: false,

		init() {
			const storedTheme = window.localStorage.getItem('theme');
			const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
			this.isDark = storedTheme ? storedTheme === 'dark' : prefersDark;
			this.applyTheme();
		},

		toggle() {
			this.isDark = !this.isDark;
			window.localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
			this.applyTheme();
		},

		applyTheme() {
			document.documentElement.classList.toggle('dark', this.isDark);
		},
	}));
});

Alpine.start();
