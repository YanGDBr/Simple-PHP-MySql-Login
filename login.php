<?php
session_start();

echo "Olá novo usuario chamado: ".$_SESSION['Nome']."<br>";
echo 'Seu Email é: '.$_SESSION['Email']."<br>";
echo 'Sua Senha criptografada em MD5 é: '.$_SESSION['Senha']."<br>";


