<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'livraria';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['Gravar'])) {
    $codlivro = $conn->real_escape_string($_POST['codlivro']);
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $nrpags = $conn->real_escape_string($_POST['nrpags']);
    $ano = $conn->real_escape_string($_POST['ano']);
    $codautor = $conn->real_escape_string($_POST['codautor']);
    $codeditora = $conn->real_escape_string($_POST['codeditora']);
    $codcategoria = $conn->real_escape_string($_POST['codcategoria']);
    $resenha = $conn->real_escape_string($_POST['resenha']);
    $preco = $conn->real_escape_string($_POST['preco']);

    $diretorio = "fotos/";
    
    if (!file_exists($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    $novo_nome1 = '';
    if (isset($_FILES['foto1']) && $_FILES['foto1']['error'] == 0) {
        $filename1 = $_FILES['foto1']['name'];
        $extensao1 = pathinfo($filename1, PATHINFO_EXTENSION);
        $novo_nome1 = md5(time() . '1') . '.' . $extensao1;
        
        if (move_uploaded_file($_FILES['foto1']['tmp_name'], $diretorio . $novo_nome1)) {
            echo "Foto 1 enviada com sucesso.<br>";
        } else {
            echo "Erro ao enviar a foto 1.<br>";
            $novo_nome1 = '';
        }
    }

    $novo_nome2 = '';
    if (isset($_FILES['foto2']) && $_FILES['foto2']['error'] == 0) {
        $filename2 = $_FILES['foto2']['name'];
        $extensao2 = pathinfo($filename2, PATHINFO_EXTENSION);
        $novo_nome2 = md5(time() . '2') . '.' . $extensao2;
        
        if (move_uploaded_file($_FILES['foto2']['tmp_name'], $diretorio . $novo_nome2)) {
            echo "Foto 2 enviada com sucesso.<br>";
        } else {
            echo "Erro ao enviar a foto 2.<br>";
            $novo_nome2 = '';
        }
    }

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

    $stmt = $conn->prepare("SELECT foto1, foto2 FROM livro WHERE codlivro = ?");
    if ($stmt) {
        $stmt->bind_param("i", $codlivro);
        $stmt->execute();
        $stmt->bind_result($foto1, $foto2);
        
        if ($stmt->fetch()) {
            if (!empty($foto1) && file_exists("fotos/" . $foto1)) {
                unlink("fotos/" . $foto1);
            }
            if (!empty($foto2) && file_exists("fotos/" . $foto2)) {
                unlink("fotos/" . $foto2);
            }
        }
        $stmt->close();
    }
    
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
    
    $diretorio = "fotos/";
    
    $stmt = $conn->prepare("SELECT foto1, foto2 FROM livro WHERE codlivro = ?");
    $foto1_atual = '';
    $foto2_atual = '';
    
    if ($stmt) {
        $stmt->bind_param("i", $codlivro);
        $stmt->execute();
        $stmt->bind_result($foto1_atual, $foto2_atual);
        $stmt->fetch();
        $stmt->close();
    }
    
    $novo_nome1 = $foto1_atual;
    if (isset($_FILES['foto1']) && $_FILES['foto1']['error'] == 0) {
        $filename1 = $_FILES['foto1']['name'];
        $extensao1 = pathinfo($filename1, PATHINFO_EXTENSION);
        $novo_nome1 = md5(time() . '1') . '.' . $extensao1;
        
        if (!empty($foto1_atual) && file_exists($diretorio . $foto1_atual)) {
            unlink($diretorio . $foto1_atual);
        }
        
        if (move_uploaded_file($_FILES['foto1']['tmp_name'], $diretorio . $novo_nome1)) {
            echo "Nova foto 1 enviada com sucesso.<br>";
        } else {
            echo "Erro ao enviar a nova foto 1.<br>";
            $novo_nome1 = $foto1_atual;
        }
    }
    
    $novo_nome2 = $foto2_atual;
    if (isset($_FILES['foto2']) && $_FILES['foto2']['error'] == 0) {
        $filename2 = $_FILES['foto2']['name'];
        $extensao2 = pathinfo($filename2, PATHINFO_EXTENSION);
        $novo_nome2 = md5(time() . '2') . '.' . $extensao2;
        
        if (!empty($foto2_atual) && file_exists($diretorio . $foto2_atual)) {
            unlink($diretorio . $foto2_atual);
        }
        
        if (move_uploaded_file($_FILES['foto2']['tmp_name'], $diretorio . $novo_nome2)) {
            echo "Nova foto 2 enviada com sucesso.<br>";
        } else {
            echo "Erro ao enviar a nova foto 2.<br>";
            $novo_nome2 = $foto2_atual;
        }
    }
    
    $stmt = $conn->prepare("UPDATE livro SET titulo = ?, nrpaginas = ?, resenha = ?, ano = ?, codautor = ?, codeditora = ?, codcategoria = ?, preco = ?, foto1 = ?, foto2 = ? WHERE codlivro = ?");
    
    if ($stmt) {
        $stmt->bind_param("sisiiiidssi", $titulo, $nrpags, $resenha, $ano, $codautor, $codeditora, $codcategoria, $preco, $novo_nome1, $novo_nome2, $codlivro);
        
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
                } else {
                    echo "Sem foto 1<br>";
                }
                
                if (!empty($dados['foto2'])) {
                    echo '<img src="fotos/' . htmlspecialchars($dados['foto2']) . '" height="200" width="200" />' . "<br><br>  ";
                } else {
                    echo "Sem foto 2<br><br>";
                }
            }
        }
        
        $stmt->close();
    } else {
        echo "Erro na preparação da query: " . $conn->error;
    }
}

$conn->close();
?>
