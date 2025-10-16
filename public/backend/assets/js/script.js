function toggleClass(id, id2, id3) {
  const element = document.getElementById(id);
  element.classList.toggle("active");
  if (id2) {
    const elementContent = document.getElementById(id2);
    elementContent.classList.toggle("active");
  }
  if (id3) {
    const elementContent = document.getElementById(id3);
    elementContent.classList.toggle("active");
  }
}
function controlLeftMenu() {
  const leftMenu = document.getElementById("left_side");
  leftMenu.classList.toggle("open");
}

// toggle dashboard card
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


$("#myModal").on("shown.bs.modal", function () {
  $("#myInput").trigger("focus");
});

// input validation
function inputValidator(e, warnText, type = "text",eyeId, length = 6) {
  const inputValue = e;
  const warnTextContent = document.getElementById(warnText);
  if (type === "text") {
    if (inputValue.length < 1) {
      warnTextContent.classList.add("active");
    } else {
      warnTextContent.classList.remove("active");
    }
  }
  if (type === "password") {
    if (inputValue.length < length) {
      if (inputValue.length === 0) {
        warnTextContent.classList.add("active");
      } else {
        warnTextContent.classList.add("active");
        warnTextContent.innerText = `Password must be ${length} character`;
      }
    } else {
      warnTextContent.classList.remove("active");
    }
    if(warnTextContent.classList.contains('active') && eyeId){
      const eye = document.getElementById(eyeId);
      eye.classList.add('active')
    }else{
      const eye = document.getElementById(eyeId);
      eye.classList.remove('active')
    }
  }
}
function showPass(id) {
  const input = document.getElementById(id);
  if (input.type === "password") {
    input.type = "text";
  } else {
    input.type = "password";
  }
}

function filterOption() {
  const filter_content = document.getElementById("filter_content");
  filter_content.classList.toggle("active");
}

function removeTag(items) {
  const filteredItems = tags.filter((item) => {
    return item !== items;
  });
  tags = filteredItems;
  renderTag();
}


// img preview

function PreviewImage(selectFile, previewImg) {
  var oFReader = new FileReader();
  oFReader.readAsDataURL(document.getElementById(selectFile).files[0]);

  oFReader.onload = function (oFREvent) {
    document.getElementById(previewImg).src = oFREvent.target.result;
  };
}

function cancelPreview(id) {
  const img = document.getElementById(id);
  img.src = "../assets/images/img_preview.png";
}

function slideToggleControl(id){
  $(document).ready(function () {
    $(`#${id}`).slideToggle()
  })
}





// remove class
document.onclick = function (e) {
  const profile = document.getElementById("profile_content");
  if (profile) {
    if (
      e.target.parentNode.id !== "profile_pic" &&
      e.target.parentNode.id !== "profile_content" &&
      e.target.parentNode.parentNode.id !== "profile_content"
    ) {
      profile.classList.remove("active");
    }
  }
};