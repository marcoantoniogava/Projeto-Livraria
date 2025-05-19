<?php
// Conectar com banco de dados
$conectar = mysqli_connect('localhost', 'root', '', 'livraria');

// Verificar se a conexão foi estabelecida
if (mysqli_connect_errno()) {
    echo "Falha ao conectar ao MySQL: " . mysqli_connect_error();
    exit();
}

if (isset($_POST['Conectar'])) {
    // Receber as variáveis do HTML
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    // Prevenir injeção SQL utilizando prepared statements
    $stmt = mysqli_prepare($conectar, "SELECT email, senha FROM usuario WHERE email = ? AND senha = ?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $senha);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) <= 0) {
        echo "<script language='javascript' type='text/javascript'>
            alert('email e/ou senha incorretos');
            window.location.href='loginusuario.html';
            </script>";
    } else {
        setcookie('email', $email);
        header('Location:menu.html');
    }
    
    // Fechar statement
    mysqli_stmt_close($stmt);
}

// Fechar conexão
mysqli_close($conectar);
?>
