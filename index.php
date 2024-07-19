<?php
session_start();
$connect = new mysqli("localhost", "root", "", "database");

if($connect-> connect_error){
    echo 'Ocorreu um erro ao tentar se conectar com o banco de dados: '.$connect->connect_error;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
if (isset($_POST['click'])) {
    if (empty($_POST['nome']) or empty($_POST['senha']) or empty($_POST['email'])) {
        echo '<section><h3>Preencha todos os campos</h3></section>';
    } else {
        $nome = $connect->real_escape_string($_POST['nome']);
        $senha = md5($connect->real_escape_string($_POST['senha']));
        $email = $connect->real_escape_string($_POST['email']);
        if ($_POST['click'] == "login") {
            $result = $connect -> query("SELECT * FROM users WHERE Nome = '$nome' AND Senha = '$senha'");
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $_SESSION['Nome'] = $row['Nome'];
                $_SESSION['Senha'] = $row['Senha'];
                $_SESSION['Email'] = $row['Email'];
                header("Location: login.php");
            }
            else {
                echo "<h3>Conta não encontrada</h3>";
            }
        }
        else{
            $resultnome = $connect->query("SELECT * FROM users WHERE Nome = '$nome'");
            $resultemail = $connect->query("SELECT * FROM users WHERE Email = '$email'");
            if ($resultnome->num_rows > 0) {
                echo "<h3>Esse nome já está associada a uma conta, utilize outro nome</h3>";
                $resultnome->free();
                $resultemail->free();
                $connect->close();
            } else if ($resultemail->num_rows > 0) {
                echo "<h3>Esse email já está associada a uma conta, utilize outra</h3>";
                $resultnome->free();
                $resultemail->free();
                $connect->close();
            }
            else {
                $sql = "INSERT INTO users (Nome, Senha, Email) VALUES ('$nome', '$senha', '$email')";
                
                if($connect -> query($sql) === TRUE){
                    echo "<h3>Conta criada com Sucesso</h3> <p>Redirecionando você em 5 segundos</p>";
                    $_SESSION['Nome'] = $_POST['nome'];
                    $_SESSION['Senha'] = $senha;
                    $_SESSION['Email'] = $_POST['email'];
                    header("Location: login.php");
                }
                else {
                    echo "<h3>Ocorreu um erro ao tentar criar a conta</h3>";
                }
            }
        }   
    }
} else {
    $connect->close();
}

?>
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
        <h2>Login</h2>
        <label for="nome">Nome</label>
        <input type="text" name="nome" placeholder="Insira seu nome">
        <label for="senha">Senha</label>
        <input type="password" name="senha" placeholder="Insira sua senha">
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="insira seu email">
        <div>
            <button type="submit" name="click" value="login">Logar</button>
            <button type="submit" name="click" value="register">Registrar</button>
        </div>
    </form>
</body>
</html>