

function autenthicated(){
    let user = document.getElementById('user').value?? '';
    let password = document.getElementById('user_password').value?? '';
    let credentials = {
        user,
        password
    }

    let datos = new FormData();

    datos.append('credentials', JSON.stringify(credentials) );
  

    $.ajax({
        url: "ajax/login.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            /* window.location.href = './stats.php'; */
           rol = JSON.parse(response);
           console.log(rol)
           
           switch (rol) {
            case 0:
                Swal.fire({
                    icon: "error",
                    title: "Login",
                    text: "Credenciales invalidas",
                });
                break;
            case 1:
                window.location.href = './welcome.php';
                break;
            case 2:
                Swal.fire({
                    icon: "error",
                    title: "Login",
                    text: "Credenciales invalidas",
                });
                break;
            default:
                Swal.fire({
                    icon: "error",
                    title: "Login",
                    text: "Credenciales invalidas",
                });
                break;
           }

        }
    });

}

function logout(){
    let datos = new FormData();

    datos.append('logout', 'logout');
  

    $.ajax({
        url: "ajax/login.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            window.location.href = './index.php';
        }
    });

}