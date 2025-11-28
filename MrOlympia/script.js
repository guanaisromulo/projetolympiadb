document.addEventListener("DOMContentLoaded", function () {

    /* ---------- LOGIN (formLogin) ---------- */
    const formLogin = document.getElementById("formLogin");
    if (formLogin) {
        formLogin.addEventListener("submit", function(e) {
            e.preventDefault();
            let fd = new FormData(formLogin);
            
            // CORREÇÃO: Usando caminho absoluto no fetch para garantir que o Apache encontre
            fetch("/projeto/PHP/login.php", { method: "POST", body: fd })
            .then(r => r.text())
            .then(txt => {
                txt = txt.trim();
                console.log("login:", txt);
                if (txt === "admin") window.location.href = "dashboard_admin.html";
                else if (txt === "usuario") window.location.href = "dashboard_usuario.html";
                else alert("Email ou senha incorretos");
            })
            .catch(()=> alert("Erro ao conectar com o servidor"));
        });
    }

    /* ---------- INSCRIÇÃO: popula categorias por gênero e valida peso ---------- */
    // Elementos do formulário
    const category = document.getElementById("category");
    const generoSel = document.getElementById("genero");
    const alturaInput = document.getElementById("altura");
    const pesoInput = document.getElementById("peso");
    const textInscricao = document.getElementById("textInscricao");
    // Campo hidden que guarda a categoria final
    const categoriaHidden = document.getElementById("categoria_final"); 

    const classicLimits = [
        { alturaMax: 163, pesoMax: 77 }, { alturaMax: 165, pesoMax: 79 },
        { alturaMax: 168, pesoMax: 82 }, { alturaMax: 170, pesoMax: 84 },
        { alturaMax: 173, pesoMax: 87 }, { alturaMax: 175, pesoMax: 91 },
        { alturaMax: 178, pesoMax: 94 }, { alturaMax: 180, pesoMax: 98 },
        { alturaMax: 183, pesoMax: 101 }, { alturaMax: 185, pesoMax: 104 },
        { alturaMax: 188, pesoMax: 109 }, { alturaMax: 191, pesoMax: 112 },
        { alturaMax: 193, pesoMax: 116 }, { alturaMax: 196, pesoMax: 119 },
        { alturaMax: 198, pesoMax: 122 }, { alturaMax: 201, pesoMax: 126 },
        { alturaMax: 999, pesoMax: 129 }
    ];

    function mudarCategoriasPorGenero() {
        if (!category || !generoSel) return;
        const g = generoSel.value;
        category.innerHTML = "";
        let lista = [];
        if (g === "masculino") {
            lista = [
                {value: "men-open", text: "Men's Open"},
                {value: "classic-physique", text: "Classic Physique"},
                {value: "212", text: "212"},
                {value: "physique", text: "Men's Physique"}
            ];
        } else {
            lista = [
                {value: "figure", text: "Figure"},
                {value: "bikini", text: "Bikini"},
                {value: "wellness", text: "Wellness"}
            ];
        }
        lista.forEach(it => {
            const opt = document.createElement('option');
            opt.value = it.value; opt.text = it.text;
            category.appendChild(opt);
        });
        calcularLimite();
    }

    function calcularLimite() {
        if (!alturaInput || !pesoInput || !textInscricao || !category || !categoriaHidden) return;

        const altura = Number(alturaInput.value || 0);
        const peso = Number(pesoInput.value || 0);
        const cat = category.value;

        if (!altura || !peso) {
            textInscricao.innerText = '';
            categoriaHidden.value = cat; 
            return;
        }

        // Lógica de validação do peso
        if (cat === "classic-physique") {
            let max = 0;
            for (let i=0;i<classicLimits.length;i++){
                if (altura <= classicLimits[i].alturaMax) {
                    max = classicLimits[i].pesoMax;
                    break;
                }
            }
            if (peso <= max) {
                textInscricao.innerText = `✔ Inscrição aprovada! Dentro do limite Classic Physique (${max}kg).`;
            } else {
                textInscricao.innerText = `⚠ Você ultrapassou ${ (peso - max).toFixed(1) } kg do limite Classic Physique.`;
            }
        } else if (cat === "212") {
            const limite = 96.6; 
            if (peso <= limite) {
                textInscricao.innerText = `✔ Inscrição aprovada! Dentro do limite 212 (${limite}kg).`;
            } else {
                textInscricao.innerText = `⚠ Você ultrapassou ${(peso - limite).toFixed(1)} kg do limite 212.`;
            }
        } else {
            textInscricao.innerText = `Categoria ${category.options[category.selectedIndex].text} selecionada.`;
        }
        

        categoriaHidden.value = cat; 
    }

    // Event Listeners
    if (generoSel) generoSel.addEventListener('change', mudarCategoriasPorGenero);
    if (category) category.addEventListener('change', calcularLimite);
    if (alturaInput) alturaInput.addEventListener('input', calcularLimite);
    if (pesoInput) pesoInput.addEventListener('input', calcularLimite);


    if (generoSel) mudarCategoriasPorGenero();


    const formCadastro = document.getElementById("formCadastro");
    if (formCadastro) {
        formCadastro.addEventListener('submit', function(e) {
            e.preventDefault(); 
            
            const fd = new FormData(formCadastro);
            

            fetch("/projeto/PHP/salvar_inscricao.php", { method: 'POST', body: fd }) 
            .then(r => r.text())
            .then(txt => {
                console.log("Resposta do PHP:", txt.trim());
                document.body.innerHTML = txt;
            })
            .catch((error)=> {
                console.error("Erro no fetch:", error);
                alert('Erro ao conectar ao servidor ou processar a inscrição.');
            });
        });
    }

}); 

