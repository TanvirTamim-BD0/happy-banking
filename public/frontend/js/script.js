function genderToggle(male,female,selected){
 const array = [male,female];
 const selectedElement = document.getElementById(selected);
 const gender = document.getElementById('genderValue');
 gender.value = ''
 array.forEach((gender) => {
    const element = document.getElementById(gender)
    element.classList.remove('active')
 })
 selectedElement.classList.add('active')
  if (selected === 'male_btn') {
    $("#genderValue").val('male');
  } else {
    $("#genderValue").val('female');
  }
}

function toggleCard(id, iconId) {
   $(document).ready(function () {
     if (iconId) {
       const icon = document.getElementById(iconId);
       if ($(`#${id}`).css("display") == "none") {
         icon.classList.remove("active");
       } else {
         icon.classList.add("active");
       }
     }
     $(`#${id}`).slideToggle();
   });
 }


 function PreviewImage(selectFile, previewImg) {
   var oFReader = new FileReader();
   oFReader.readAsDataURL(document.getElementById(selectFile).files[0]);
 
   oFReader.onload = function (oFREvent) {
     document.getElementById(previewImg).src = oFREvent.target.result;
   };
 }
 
 function cancelPreview(id) {
   const img = document.getElementById(id);
   img.src = "./images/img_preview.png";
 }

 
 document.getElementsByClassName('mobile_body')[0].addEventListener('scroll',()=>{
  document.querySelector('.no_scroll').classList.add('active')
 })
 setInterval(()=>{
   document.querySelector('.no_scroll').classList.remove('active')
 }, 3000);
 

 /* Password show added in frontend login, registration page  */

 $(document).ready(function(){

  $(document).on('click','.frontend-password-eye-icon.password-hide',function(){
    $('.frontend-login-password-field,.frontend-register-password-field').attr('type', 'text');
    $('.frontend-password-eye-icon.password-hide').css('visibility', 'hidden');
    $('.frontend-password-eye-icon.password-show').css('visibility', 'visible');
  });
 
  $(document).on('click','.frontend-password-eye-icon.password-show',function(){
   $('.frontend-login-password-field,.frontend-register-password-field').attr('type', 'password');
    $('.frontend-password-eye-icon.password-show').css('visibility', 'hidden');
    $('.frontend-password-eye-icon.password-hide').css('visibility', 'visible');
  });

 });






