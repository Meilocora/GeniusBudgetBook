"use strict";

function switchValues() {
    document.querySelector(".updateButton").addEventListener("click", e => {
        let entryId = e.target.form.id.value;
    
        let categoryValue = document.getElementById(`${entryId}-category`).value;
        let new_category_element = e.target.form.category;
        new_category_element.setAttribute("value", categoryValue);
    
        let titleValue = document.getElementById(`${entryId}-title`).value;
        let new_title_element = e.target.form.title;
        new_title_element.setAttribute("value", titleValue);
    
        let amountValue = document.getElementById(`${entryId}-amount`).value;
        let new_amount_element = e.target.form.amount;
        new_amount_element.setAttribute("value", amountValue);
    
        let dateslugValue = document.getElementById(`${entryId}-dateslug`).value;
        let new_dateslug_element = e.target.form.dateslug;
        new_dateslug_element.setAttribute("value", dateslugValue);
    
        let commentValue = document.getElementById(`${entryId}-comment`).value;
        let new_comment_element = e.target.form.comment;
        new_comment_element.setAttribute("value", commentValue);
    
        let fixedentrybox = document.getElementById(`${entryId}-fixedentry`).checked;
        console.log(fixedentrybox);
        let fixedentryValue;
        if(fixedentrybox === true) {
            fixedentryValue = 1;
        } else {
            fixedentryValue = 0;
        }
        let new_fixedentry_element = e.target.form.fixedentry;
        new_fixedentry_element.setAttribute("value", fixedentryValue);
    }); 
}

switchValues();
        