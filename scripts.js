let form = $('#form1');
let formAuth = $('#form2');

form.on('submit', register);
formAuth.on('submit', login);


function register (event) {
    event.preventDefault();

    const log = $('#login').val();
    const pas = $('#passw').val();
    const email = $('#email').val();
    const btnNode = event.target.querySelector('input[type="submit"]');

    $.ajax({
        method: "POST",
        url: "/register.php",
        data: {
            login: log,
            password: pas,
            email: email
        },
        beforeSend: () => {
            btnNode.setAttribute('disabled', true);
        },
        success: (data) => {
            if (data.response.error) {
                alert(`Ошибка: ${data.response.error}`)
            }
            if (data.response.text) {
                alert(`${data.response.text}`)
            }
            setTimeout(function () {
                location.href = "/register.html";
            }, 1000);
            btnNode.removeAttribute("disabled");
        }
    });
}

function login (event) {
    event.preventDefault();
    
    const log = $('#login').val();
    const pas = $('#passw').val();

    $.ajax({
        method: "POST",
        url: "/authorize.php",
        data: {
            login: log,
            password: pas
        },
        success: (data) => {
            if (data.response.error) {
                alert(`Ошибка: ${data.response.error}`)
            }
            if (data.response.text) {
                alert(`${data.response.text}`)
                localStorage.setItem('token',data.user.token)
                localStorage.setItem('id',data.user.id)
            }
        }
    });
}