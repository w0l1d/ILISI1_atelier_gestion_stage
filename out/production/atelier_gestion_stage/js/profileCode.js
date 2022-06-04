const themeToggle=document.querySelector(".theme-toggler");
//////////////////////////
const tabBtn=document.querySelectorAll(".tab");
const tab=document.querySelectorAll(".tabShow");
//////////////////////
 const imgdiv=document.querySelector(".profile-photo");
const img=document.querySelector("#image");
const file=document.querySelector("#file");
const uploadBtn=document.querySelector("#uploadBtn");
const download=document.querySelector("#download");
const filePdf=document.querySelector("#file2");

themeToggle.addEventListener('click',() => {
    document.body.classList.toggle('dark-theme-variables');
    themeToggle.querySelector('span:nth-child(1)').classList.toggle('active');
    themeToggle.querySelector('span:nth-child(2)').classList.toggle('active');
 });
 
/******panel index***** */
function tabs(panelIndes){
    tab.forEach(function(node)
    {
        node.style.display="none";
    });
    tab[panelIndes].style.display="block";

}
tabs(0);
$(".tab ").click(function()
    {   
        $(this).addClass("active").siblings().removeClass("active");
    });

    /****file */
    imgdiv.addEventListener('mouseenter',() =>{
        uploadBtn.style.display="block";
    });
    imgdiv.addEventListener('mouseleave',() =>{
        uploadBtn.style.display="none";
    }); 
    file.addEventListener('change',() =>{
       const choosedFile = document.getElementById('file').files[0];
       if(choosedFile){
           const reader =new FileReader();
           reader.addEventListener('load',() =>{
               img.setAttribute('src',reader.result);
           });
           reader.readAsDataURL(choosedFile);
       }
    });

    filePdf.addEventListener('change',() =>{
        const choosedFile = document.getElementById('file2').files[0];
        if(choosedFile){
            const reader =new FileReader();
            reader.addEventListener('load',() =>{
                 
                download.setAttribute('src',reader.result );
                 
            });
            reader.readAsDataURL(choosedFile);
        }
       
        
     });
 
//mode night

 
