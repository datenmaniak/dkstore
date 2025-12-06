/*! modernizr 3.6.0 (Custom Build) | MIT *
 * https://modernizr.com/download/?-setclasses !*/
!function(n,e,s){function o(n){var e=f.className,s=Modernizr._config.classPrefix||"";if(l&&(e=e.baseVal),Modernizr._config.enableJSClass){var o=new RegExp("(^|\\s)"+s+"no-js(\\s|$)");e=e.replace(o,"$1"+s+"js$2")}Modernizr._config.enableClasses&&(e+=" "+s+n.join(" "+s),l?f.className.baseVal=e:f.className=e)}function a(n,e){return typeof n===e}function i(){var n,e,s,o,i,f,l;for(var c in r)if(r.hasOwnProperty(c)){if(n=[],e=r[c],e.name&&(n.push(e.name.toLowerCase()),e.options&&e.options.aliases&&e.options.aliases.length))for(s=0;s<e.options.aliases.length;s++)n.push(e.options.aliases[s].toLowerCase());for(o=a(e.fn,"function")?e.fn():e.fn,i=0;i<n.length;i++)f=n[i],l=f.split("."),1===l.length?Modernizr[l[0]]=o:(!Modernizr[l[0]]||Modernizr[l[0]]instanceof Boolean||(Modernizr[l[0]]=new Boolean(Modernizr[l[0]])),Modernizr[l[0]][l[1]]=o),t.push((o?"":"no-")+l.join("-"))}}var t=[],f=e.documentElement,l="svg"===f.nodeName.toLowerCase(),r=[],c={_version:"3.6.0",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(n,e){var s=this;setTimeout(function(){e(s[n])},0)},addTest:function(n,e,s){r.push({name:n,fn:e,options:s})},addAsyncTest:function(n){r.push({name:null,fn:n})}},Modernizr=function(){};Modernizr.prototype=c,Modernizr=new Modernizr,i(),o(t),delete c.addTest,delete c.addAsyncTest;for(var u=0;u<Modernizr._q.length;u++)Modernizr._q[u]();n.Modernizr=Modernizr}(window,document);
/* .
  Cambia el texto: activo / inactivo 
. */
document.addEventListener('DOMContentLoaded', function () {
  const checkbox = document.getElementById('is_active');
  const estadoTexto = document.getElementById('estado-notificacion');

  function actualizarEstado() {
    estadoTexto.textContent = checkbox.checked ? ' Activo' : ' Inactivo';
  }

  checkbox.addEventListener('change', actualizarEstado);
});

(() => {
    document.addEventListener('DOMContentLoaded', function () {

        darkMode()


    });


    function darkMode() {

        const preferredDarkMode = window.matchMedia('(prefers-color-scheme: dark)');

        console.log('Current theme: ', preferredDarkMode.matches ? 'dark' : 'light');

        if (preferredDarkMode.matches) {
            document.body.classList.add('dark-mode');
        } else {

            document.body.classList.remove('dark-mode');

        }

        preferredDarkMode.addEventListener('change', (event) => {
            if (event.matches) {
                document.body.classList.add('dark-mode');
            } else {
                document.body.classList.remove('dark-mode');
            }

        });

        const darkModeBtn = document.querySelector('.dark-mode-button');

        // Escuchamos el click
        darkModeBtn.addEventListener('click', () => {
            darkModeBtn.classList.toggle('active');
            document.body.classList.toggle('dark-mode');
            console.log('Dark mode active');
        });

    }
})();


/* admin dashboard */

(function () {
    document.querySelectorAll('.sidebar button').forEach(btn => {
        btn.addEventListener('click', () => {
            const section = btn.dataset.section;

            fetch(`/admin/${section}/${section}.php`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('crud-area').innerHTML = html;
                })
                .catch(err => {
                    document.getElementById('crud-area').innerHTML = "<p>Error al cargar la sección.</p>";
                    console.error(err);
                });
        });
    });
})();

/* 

toggleMenu 

*/

(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.querySelector('.menuToggle');
    const navbar = document.querySelector('.nav__options');
    const overlay = document.querySelector('.overlay');
    const mobileMenu = document.querySelector('.mobileMenu');
    /*  Login Button  */
    const navLogin = document.querySelector('.nav__login');


    if (!menuToggle || !navbar) return;


    const toggleMenu = () => {
      const isOpen = navbar.classList.toggle('active');
      menuToggle.classList.toggle('active');
      mobileMenu.classList.toggle('active');
      overlay?.classList.toggle('active');

      // Mostrar u ocultar login según estado
      navLogin.classList.toggle('active', isOpen);

      menuToggle.setAttribute('aria-expanded', isOpen);
      navbar.setAttribute('aria-hidden', !isOpen);
    };

    const closeMenu = () => {
      navbar.classList.remove('active');
      menuToggle.classList.remove('active');
      mobileMenu.classList.remove('active');
      overlay?.classList.remove('active');
      menuToggle.setAttribute('aria-expanded', false);
      navbar.setAttribute('aria-hidden', true);
      navLogin.classList.remove('active'); // ocultar login al cerrar
    };

    menuToggle.addEventListener('click', toggleMenu);
    mobileMenu.addEventListener('click', closeMenu);
    overlay?.addEventListener('click', closeMenu);
    // mobileMenu.addEventListener('click', mobileMenu);

    // Opcional: cerrar con ESC
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && navbar.classList.contains('active')) {
        closeMenu();
      }
    });

    // Cerrar al hacer click en cualquier enlace del menú
    navbar.addEventListener('click', (e) => {
      const link = e.target.closest('a');
      if (!link) return;

      // Cierra el menú inmediatamente
      if (navbar.classList.contains('active')) {
        closeMenu();
      }

    });

    // Resetea el menú si el viewport supera el breakpoint tablet
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 768) { // breakpoint tablet
        closeMenu();
        // navbar.classList.remove('active');
        // overlay?.classList.remove('active');
        // menuToggle.classList.remove('active');
        // menuToggle.setAttribute('aria-expanded', false);
        // En tablet/desktop el menú no debe estar "aria-hidden"
        navbar.setAttribute('aria-hidden', false);
      }
    });
  });
})();

/* 
// Snippet mínimo para alternar la clase
//  .scrolled 
// */
(function () {
    const header = document.querySelector('[data-header]');
    // const headerContainer = document.querySelector('.header__container');

    if (!header) return;

    const SCROLL_THRESHOLD = 10; // píxeles desde el top para activar

    const onScroll = () => {
        if (window.scrollY > SCROLL_THRESHOLD) {
            header.classList.add('scrolled');
            // headerContainer.classList.add('home');
        } else {
            header.classList.remove('scrolled');
        }
    };

    // Inicial y listeners
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
})();

// Snippet para manejar flag 'home' en el headerContainer

(function () {
    document.addEventListener("DOMContentLoaded", () => {
        const headerContainer = document.querySelector(".header__container");
        if (!headerContainer) return; // seguridad: si no existe, no hace nada

        // Detecta la página actual
        const currentPage = window.location.pathname.split("/").pop();

        // Aplica la clase 'home' solo en index.php o raíz
        if (currentPage === "index.html" || currentPage === "") {
            headerContainer.classList.remove("bg-active");
        } else {
            headerContainer.classList.add("bg-active");
        }

        // any other index without hero, no count!
        // if (currentPage === "/admin/index.php") {
        //     // if (currentPage === "/admin/index.php" || currentPage === "") {
        //     headerContainer.classList.add("bg-active");
        // } else {
        //     headerContainer.classList.remove("bg-active");
        // }

    });
})();