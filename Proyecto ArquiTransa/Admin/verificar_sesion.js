// Verificar si la cookie está activa al cargar la página
window.onload = function() {
    verificarCookie();
};

function verificarCookie(nombre) {
    // Obtener todas las cookies del navegador
    var cookies = document.cookie.split(';');

    // Buscar la cookie específica por su nombre
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        while (cookie.charAt(0) === ' ') {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(nombre + '=') === 0) {
            // La cookie está activa
            return true;
        }
    }

    // La cookie no está activa
    return false;
}
// Verificar si la cookie 'email' está activa
var emailCookieActiva = verificarCookie('email');

if (emailCookieActiva) {
    console.log('La cookie "email" está activa.');
window.history.pushState(null, null, window.location.href);
window.onpopstate = function(event) {
    window.history.go(1); // Redirigir al usuario nuevamente al login
};
} else {
    console.log('La cookie "email" no está activa.');
window.location.href = '../LoginAdm/login.html';
window.history.replaceState({}, document.title, "../LoginAdm/login.html");
}

