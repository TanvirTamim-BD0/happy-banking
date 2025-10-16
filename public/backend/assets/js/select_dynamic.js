let saveData = {};
const dataLists = {
  role: ["admin", "user"],
  lng: ["bn", "en", "hindi"],
  currency: ['USD&nbsp;-&nbsp;USA dollar','GBP&nbsp;-&nbsp;British pound','AUD&nbsp;-&nbsp;Australian dollar','JPY&nbsp;-&nbsp;Japanese yen','SEK&nbsp;-&nbsp;Swedish krona','CAD&nbsp;-&nbsp;Canadian dollar','CHF&nbsp;-&nbsp;Swiss franc']
};
function openSelectMenu(wrapper, option, input, buttonId, data,zIndex) {
  saveData = {
    wrapper: wrapper,
    option: option,
    input: input,
    btn: buttonId,
    data: data,
    zIndex: zIndex
  };

  const wrapperElement = document.getElementById(wrapper);
  const options = document.getElementById(option);
  const zIndexElement = document.getElementById(zIndex);
  // const zIndexPoint = zIndex.split('_')[2];
  // zIndexElement.style.zIndex = `${10*zIndexPoint}`
  wrapperElement.classList.toggle("active");
  options.innerHTML = "";
  dataLists[`${data}`].forEach((item, index) => {
    let li = `<li id="classUpdate_${index}" onclick="updateRoleName(this,'classUpdate_${index}')">${item}</li>`;
    options.insertAdjacentHTML("beforeend", li);
  });
}

function updateRoleName(selectedLi,id) {
  const { wrapper, option, input, btn, data,zIndex } = saveData;
  const search = document.getElementById(input);
  // if(id){
  //   const element = document.getElementById(id)
  //   element.style.background = 'red'
  //   console.log(element)
  // }
  search.value = "";

  openSelectMenu(wrapper, option, input, btn, data, zIndex);
  document.getElementById(wrapper).classList.remove("active");
  document.getElementById(btn).firstElementChild.innerText =
    selectedLi.innerText;
}

function search() {
  let arr = [];
  const searchElement = document.getElementById(saveData.input);
  let searchedValue = searchElement.value.toLowerCase();
  arr = dataLists[`${saveData.data}`]
    .filter((data) => {
      return data.toLowerCase().startsWith(searchedValue);
    })
    .map((data) => `<li onclick="updateRoleName(this)">${data}</li>`)
    .join("");
  const optionElement = document.getElementById(saveData.option);
  optionElement.innerHTML = arr ? arr : `<p style="color: red">Wrong Input</p>`;
}
