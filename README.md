# 📚 Wiki Escolar - Plataforma Colaborativa de Estudo

![Status](https://img.shields.io/badge/Status-Em_Desenvolvimento-success?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Bootstrap](https://img.shields.io/badge/Bootstrap_5-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)

A **Wiki Escolar** é uma plataforma web desenvolvida para fomentar a criação colaborativa de conhecimento dentro do ambiente escolar. Os alunos podem enviar termos, conceitos e artigos de diferentes matérias, que passam por uma moderação criteriosa dos professores antes de serem publicados na base de conhecimento da escola.

---

## ✨ Funcionalidades Principais

### 🎓 Visão do Aluno (Frontend)
* **Exploração de Conteúdo:** Busca e leitura de artigos aprovados divididos por matérias.
* **Envio de Termos:** Formulário intuitivo para submissão de novos conceitos, com suporte ao envio de imagens explicativas.
* **Vínculo Escolar:** Identificação do autor através de Nome e Turma/RM.

### 👨‍🏫 Visão do Professor (Painel Administrativo)
* **Fila de Moderação:** Sistema de status para cada artigo submetido:
  * 🟡 *Pendente:* Aguardando revisão.
  * 🟢 *Aprovado (Publicado):* Visível para todos os alunos.
  * 🔴 *Em Averiguação:* Retido para correção ou conversa com o aluno.
* **Edição de Conteúdo:** Capacidade de corrigir textos e alterar imagens de artigos submetidos antes da publicação.
* **Gestão de Matérias:** Criação, edição, exclusão e reordenação (drag & drop/setas) das disciplinas (ex: Matemática, História) com suporte a ícones.
* **Gestão de Turmas:** Cadastro e manutenção das turmas ativas e seus respectivos RMs.

---

## 🛠️ Tecnologias Utilizadas

* **Frontend:** HTML5, CSS3, JavaScript (Vanilla ES6+), Bootstrap 5.
* **Backend:** PHP (API RESTful simplificada estruturada com `switch/case`).
* **Banco de Dados:** MySQL (Comunicação segura utilizando `PDO` e *Prepared Statements* contra SQL Injection).
* **Integração:** Fetch API para requisições assíncronas (AJAX), processamento de imagens em `Base64`.

---

## 🚀 Como executar o projeto localmente

### Pré-requisitos
Você precisará de um servidor local Apache com suporte a PHP e MySQL (recomenda-se [XAMPP](https://www.apachefriends.org/pt_br/index.html), WAMP ou Laragon).

### Passos para instalação

1. **Clone o repositório**
   ```bash
   git clone [https://github.com/SEU_USUARIO/NOME_DO_REPOSITORIO.git](https://github.com/SEU_USUARIO/NOME_DO_REPOSITORIO.git)