const wrapper = document.querySelector(".search_wrapper");

if (wrapper) {
  const selectBtn = wrapper.querySelector(".select-btn");
  const options = wrapper.querySelector(".options");
  const searchInput = wrapper.querySelector("input");
  let role = ["Admin", "User"];

  function addRole() {
    options.innerHTML = "";
    role.forEach((country) => {
      let li = `<li onclick="updateRoleName(this)">${country}</li>`;
      options.insertAdjacentHTML("beforeend", li);
    });
  }
  addRole();
  function updateRoleName(selectedLi) {
    console.log(selectedLi.innerText);
    searchInput.value = "";
    addRole();
    wrapper.classList.remove("active");
    selectBtn.firstElementChild.innerText = selectedLi.innerText;
  }
  searchInput.addEventListener("keyup", () => {
    let arr = [];
    let searchedValue = searchInput.value.toLowerCase();
    arr = role
      .filter((data) => {
        return data.toLowerCase().startsWith(searchedValue);
      })
      .map((data) => `<li onclick="updateRoleName(this)">${data}</li>`)
      .join("");
    options.innerHTML = arr ? arr : `<p style="color: red">Wrong Input</p>`;
  });

  selectBtn.addEventListener("click", () => {
    wrapper.classList.toggle("active");
  });
}

// 2nd options

const wrapper_2 = document.querySelector(".search_wrapper_2");
if (wrapper_2) {
  const selectBtn_2 = wrapper_2.querySelector(".select-btn");
  const options_2 = wrapper_2.querySelector(".options");
  const searchInput_2 = wrapper_2.querySelector("input");

  let securityOptions = ["Admin", "User"];

  function towStepOption() {
    options_2.innerHTML = "";
    securityOptions.forEach((country) => {
      let li = `<li onclick="updateName(this)">${country}</li>`;
      options_2.insertAdjacentHTML("beforeend", li);
    });
  }
  towStepOption();
  function updateName(selectedLi) {
    // console.log(selectedLi.innerText);
    searchInput_2.value = "";
    towStepOption();
    wrapper_2.classList.remove("active");
    selectBtn_2.firstElementChild.innerText = selectedLi.innerText;
  }
  searchInput_2.addEventListener("keyup", () => {
    let arr = [];
    let searchedValue = searchInput_2.value.toLowerCase();
    arr = securityOptions
      .filter((data) => {
        return data.toLowerCase().startsWith(searchedValue);
      })
      .map((data) => `<li onclick="updateName(this)">${data}</li>`)
      .join("");
    options_2.innerHTML = arr ? arr : `<p style="color: red">Wrong Input</p>`;
  });

  selectBtn_2.addEventListener("click", () => {
    wrapper_2.classList.toggle("active");
  });
}
