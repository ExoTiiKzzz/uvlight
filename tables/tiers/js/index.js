//create button

const createBtn = document.querySelector(".createBtn");

createBtn.addEventListener("click", (e) =>{

   let form = document.querySelector(".createForm");

    let formData = new FormData(form);
    formData.append("create", "1");
    fetch(url,
        {
            method: "POST",
            body: formData
        }
    )
        .then(response => response.json())
        .then(result => {
            if(result.error === true){
                errorHandler(result.errortext);
            }else{
                drawTable();
                document.querySelector(".createCloseBtn").click();
            }
        })
        .catch(err => errorHandler(err));
})

//updates buttons

function updateModal(event){
    const id = event.target.dataset.index;

    let formData = new FormData();
    formData.append("getData", "1");
    formData.append("id", id);

    fetch(url,
        {
            method: "POST",
            body: formData
        }
    )
        .then(response => response.json())
        .then(data => {
            if(data.error === true){
                errorHandler(data.errortext);
            }else{
                document.querySelectorAll(".typsoOption , .typreOption, .typtiOption, .codetarOption").forEach(el => { el.selected = false; })

                document.querySelector(".updateId").value = data.content.tie_ID;
                document.querySelector(".updateRaisonSociale").value = data.content.tie_raison_sociale;
                document.querySelector(".typsoOption[data-index='"+data.content.fk_typso_ID+"']").selected = true;
                document.querySelector(".updateTel").value = data.content.tie_tel;
                document.querySelector(".updateMail").value = data.content.tie_email;
                document.querySelector(".updateAdresse").value = data.content.tie_adresse;
                document.querySelector(".updateVille").value = data.content.tie_ville;
                document.querySelector(".typtiOption[data-index='"+data.content.fk_typti_ID+"']").selected = true;
                document.querySelector(".typreOption[data-index='"+data.content.fk_typre_ID+"']").selected = true;
                document.querySelector(".updateNumCompte").value = data.content.tie_num_compte;
                document.querySelector(".updateIBAN").value = data.content.tie_IBAN;
                document.querySelector(".updateBIC").value = data.content.tie_BIC;
                document.querySelector(".updateBanque").value = data.content.tie_code_banque;
                document.querySelector(".updateGuichet").value = data.content.tie_code_guichet;
                document.querySelector(".updateCle").value = data.content.tie_cle_rib;
                document.querySelector(".codetarOption[data-index='"+data.content.fk_tar_ID+"']").selected = true;
                document.querySelector(".updateDomi").value = data.content.tie_domiciliation;
            }
        }).catch(err => errorHandler(err));
}