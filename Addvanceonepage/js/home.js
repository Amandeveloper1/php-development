console.log('this is in.');

let susername = document.getElementById('susername');
let semail = document.getElementById('semail');
let osubmit = document.getElementById('osubmit');

let sotp = document.getElementById('sotp');
let spassword = document.getElementById('spassword');
let ssubmit = document.getElementById('ssubmit');

let lusername = document.getElementById('lemail');
let lpassword = document.getElementById('lpassword');
let lsubmit = document.getElementById('lsubmit');

let validsusername = false;
let validsemail = false;
let validsotp = false;
let validspassword = false;

let validlemail = false;
let validlpassword = false;

osubmit.disabled = true;
ssubmit.disabled = true;
lsubmit.disabled = true;

susername.addEventListener('blur', () => {
    console.log('this is blured.');
    let regex = /^[a-zA-Z]{2,10}$/;
    let str = susername.value;
    console.log(regex, str);
    if (regex.test(str)) {
        susername.classList.remove('is-invalid');
        validsusername = true;
        if (validsusername && validsemail) {
            osubmit.disabled = false;
        }
    } else {
        susername.classList.add('is-invalid');
        validsusername = false;
        osubmit.disabled = true;
    }
})

semail.addEventListener('blur', () => {
    console.log('this is blured.');
    let regex = /^([_\-\.0-9a-zA-Z]+)@([_\-\.0-9a-zA-Z]+)\.([A-Za-z]){2,7}$/;
    let str = semail.value;
    console.log(regex, str);
    if (regex.test(str)) {
        semail.classList.remove('is-invalid');
        validsemail = true;
        if (validsusername && validsemail) {
            osubmit.disabled = false;
        }
    } else {
        semail.classList.add('is-invalid');
        validsemail = false;
        osubmit.disabled = true;
    }
})

sotp.addEventListener('blur', () => {
    console.log('this is blured.');
    let regex = /^([0-9]){5}$/;
    let str = sotp.value;
    console.log(regex, str);
    if (regex.test(str)) {
        console.log('this is match name.');
        sotp.classList.remove('is-invalid');
        validsotp = true;
        if (validsotp && validspassword) {
            ssubmit.disabled = false;
        }
    } else {
        console.log('this is not match name.');
        sotp.classList.add('is-invalid');
        validsotp = false;
        ssubmit.disabled = true;
    }
})

spassword.addEventListener('blur', () => {
    console.log('this is blured.');
    let regex = /([a-zA-Z0-9])/;
    let str = spassword.value;
    console.log(regex, str);
    if (regex.test(str)) {
        console.log('this is match name.');
        spassword.classList.remove('is-invalid');
        validspassword = true;
        if (validsotp && validspassword) {
            ssubmit.disabled = false;
        }
    } else {
        console.log('this is not match name.');
        spassword.classList.add('is-invalid');
        validspassword = false;
        ssubmit.disabled = true;
    }
})

lusername.addEventListener('blur', () => {
    console.log('this is blured.');
    let regex = /^([_\-\.0-9a-zA-Z]+)@([_\-\.0-9a-zA-Z]+)\.([A-Za-z]){2,7}$/;
    let str = lusername.value;
    console.log(regex, str);
    if (regex.test(str)) {
        console.log('this is match name.');
        lusername.classList.remove('is-invalid');
        validlusername = true;
        if (validlusername && validlpassword) {
            lsubmit.disabled = false;
        }
    } else {
        console.log('this is not match name.');
        lusername.classList.add('is-invalid');
        validlusername = false;
        lsubmit.disabled = true;
    }
})

lpassword.addEventListener('blur', () => {
    console.log('this is blured.');
    let regex = /([a-zA-Z0-9])/;
    let str = lpassword.value;
    console.log(regex, str);
    if (regex.test(str)) {
        console.log('this is match name.');
        lpassword.classList.remove('is-invalid');
        validlpassword = true;
        if (validlusername && validlpassword) {
            lsubmit.disabled = false;
        }
    } else {
        console.log('this is not match name.');
        lpassword.classList.add('is-invalid');
        validlpassword = false;
        lsubmit.disabled = true;
    }
})