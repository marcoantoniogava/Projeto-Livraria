<?php
// Connect to server and database using mysqli
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'livraria';

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['Gravar'])) {
    // Use prepared statements to prevent SQL injection
    $codlivro = $conn->real_escape_string($_POST['codlivro']);
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $nrpags = $conn->real_escape_string($_POST['nrpags']);
    $ano = $conn->real_escape_string($_POST['ano']);
    $codautor = $conn->real_escape_string($_POST['codautor']);
    $codeditora = $conn->real_escape_string($_POST['codeditora']);
    $codcategoria = $conn->real_escape_string($_POST['codcategoria']);
    $resenha = $conn->real_escape_string($_POST['resenha']);
    $preco = $conn->real_escape_string($_POST['preco']);

    // Create directory and move uploaded files
    $diretorio = "fotos/";
    
    // Make sure the directory exists
    if (!file_exists($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    // Process first image
    $novo_nome1 = '';
    if (isset($_FILES['foto1']) && $_FILES['foto1']['error'] == 0) {
        $extensao1 = strtolower(substr($_FILES['foto1']['name'], -4));
        $novo_nome1 = md5(time() . '1' . $extensao1) . $extensao1;
        move_uploaded_file($_FILES['foto1']['tmp_name'], $diretorio . $novo_nome1);
    }

    // Process second image
    $novo_nome2 = '';
    if (isset($_FILES['foto2']) && $_FILES['foto2']['error'] == 0) {
        $extensao2 = strtolower(substr($_FILES['foto2']['name'], -4));
        $novo_nome2 = md5(time() . '2' . $extensao2) . $extensao2;
        move_uploaded_file($_FILES['foto2']['tmp_name'], $diretorio . $novo_nome2);
    }

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO livro (codlivro, titulo, nrpaginas, ano, codautor, codeditora, codcategoria, resenha, preco, foto1, foto2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("isiiiiisdss", $codlivro, $titulo, $nrpags, $ano, $codautor, $codeditora, $codcategoria, $resenha, $preco, $novo_nome1, $novo_nome2);
        
        $resultado = $stmt->execute();
        
        if ($resultado) {
            echo "Dados gravados com sucesso";
        } else {
            echo "Erro ao gravar dados: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Erro na preparação da query: " . $conn->error;
    }
}

if (isset($_POST['Excluir'])) {
    $codlivro = $conn->real_escape_string($_POST['codlivro']);
    
    // Use prepared statement for delete operation
    $stmt = $conn->prepare("DELETE FROM livro WHERE codlivro = ?");
    
    if ($stmt) {
        $stmt->bind_param("i", $codlivro);
        
        $resultado = $stmt->execute();
        
        if ($resultado) {
            echo "Exclusão realizada com sucesso";
        } else {
            echo "Erro ao excluir dados: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Erro na preparação da query: " . $conn->error;
    }
}

if (isset($_POST['Alterar'])) {
    $codlivro = $conn->real_escape_string($_POST['codlivro']);
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $nrpags = $conn->real_escape_string($_POST['nrpags']);
    $ano = $conn->real_escape_string($_POST['ano']);
    $codautor = $conn->real_escape_string($_POST['codautor']);
    $codeditora = $conn->real_escape_string($_POST['codeditora']);
    $codcategoria = $conn->real_escape_string($_POST['codcategoria']);
    $resenha = $conn->real_escape_string($_POST['resenha']);
    $preco = $conn->real_escape_string($_POST['preco']);
    
    // Use prepared statement for update operation
    $stmt = $conn->prepare("UPDATE livro SET titulo = ?, nrpaginas = ?, resenha = ?, ano = ?, codautor = ?, codeditora = ?, codcategoria = ?, preco = ? WHERE codlivro = ?");
    
    if ($stmt) {
        $stmt->bind_param("sisiiiidi", $titulo, $nrpags, $resenha, $ano, $codautor, $codeditora, $codcategoria, $preco, $codlivro);
        
        $resultado = $stmt->execute();
        
        if ($resultado) {
            echo "Dados alterados com sucesso";
        } else {
            echo "Erro ao alterar dados: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Erro na preparação da query: " . $conn->error;
    }
}

if (isset($_POST['Pesquisar'])) {
    // Use prepared statement for select operation
    $stmt = $conn->prepare("SELECT codlivro, titulo, nrpaginas, ano, codautor, codeditora, codcategoria, resenha, preco, foto1, foto2 FROM livro");
    
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            echo "Desculpe, mas sua pesquisa não retornou resultados.";
        } else {
            echo "<b>Livros Cadastrados:</b><br><br>";
            
            while ($dados = $result->fetch_assoc()) {
                echo "Livro: " . htmlspecialchars($dados['codlivro']) . " "; 
                echo "Título: " . htmlspecialchars($dados['titulo']) . "<br>";
                echo "Categoria: " . htmlspecialchars($dados['codcategoria']) . " ";
                echo "Resenha: " . htmlspecialchars($dados['resenha']) . " ";
                echo "Editora: " . htmlspecialchars($dados['codeditora']) . "";  
                echo "Nr pags: " . htmlspecialchars($dados['nrpaginas']) . "<br>";
                echo "Ano: " . htmlspecialchars($dados['ano']) . " ";
                echo "Autor: " . htmlspecialchars($dados['codautor']) . "<br>";
                echo "Preco: " . htmlspecialchars($dados['preco']) . " ";
                
                if (!empty($dados['foto1'])) {
                    echo '<img src="fotos/' . htmlspecialchars($dados['foto1']) . '" height="200" width="200" />' . "  ";
                }
                
                if (!empty($dados['foto2'])) {
                    echo '<img src="fotos/' . htmlspecialchars($dados['foto2']) . '" height="200" width="200" />' . "<br><br>  ";
                }
            }
        }
        
        $stmt->close();
    } else {
        echo "Erro na preparação da query: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
