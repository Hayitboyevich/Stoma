const form = document.querySelector("#fileForm"),
  fileInput = document.querySelector(".file-input"),
  progressArea = document.querySelector(".progress-area"),
  uploadedArea = document.querySelector(".uploaded-area"),
  clearBtn = document.getElementById("clearBtn");

form.addEventListener("click", () => {
  fileInput.click();
});


fileInput.onchange = ({ target }) => {
  let file = target.files[0];
  console.log(file);
  if (file) {
    let fileName = file.name;
    if (fileName.length >= 12) {
      let splitName = fileName.split(".");
      fileName = splitName[0].substring(0, 13) + "... ." + splitName[1];
    }
    uploadFile(fileName);
  }
};

function handleClear($obj) {
    $obj.closest('.row').remove();
    fileInput.value = "";
}

function uploadFile(name) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/patient/upload");
  xhr.upload.addEventListener("progress", ({ loaded, total }) => {
    let fileLoaded = Math.floor((loaded / total) * 100);
    let fileTotal = Math.floor(total / 1000);
    let fileSize;
    fileTotal < 1024
      ? (fileSize = fileTotal + " KB")
      : (fileSize = (loaded / (1024 * 1024)).toFixed(2) + " MB");
    let progressHTML = `<li class="row">
                          <img src="/img/file-icon.svg" alt="file-icon">
                          <div class="content">
                            <div class="details">
                              <span class="name">${name} • Uploading</span>
                              <span class="percent">${fileLoaded}%</span>
                            </div>
                            <div class="progress-bar">
                              <div class="progress" style="width: ${fileLoaded}%"></div>
                            </div>
                          </div>
                        </li>`;
    uploadedArea.classList.add("onprogress");
    progressArea.innerHTML = progressHTML;
    if (loaded == total) {
      progressArea.innerHTML = "";
      let uploadedHTML = `<li class="row">
                            <div class="row_left">
                                <div class="content uploaded">
                                    <img src="/img/file-icon.svg" alt="file-icon">
                                    <div class="details">
                                        <span class="name">${name} • Done</span>
                                        <span class="size">${fileSize}</span>
                                    </div>
                                </div>
                                <img src="/img/icon-check.svg" alt="file-icon"> 
                            </div>
                            <img src="/img/IconClose.svg" alt="IconClose" class="delete-file">                           
                          </li>`;
      uploadedArea.classList.remove("onprogress");
      uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);
    }
  });
  let data = new FormData(form);
  let patient_id = $('#upload_patient_id').val();
  data.append(csrfParam, csrfToken);
  data.append('file_title',$('#file_title').val());
  data.append('file_description',$('#file_description').val());
  data.append('patient_id',patient_id);
  xhr.send(data);
}

$('body').on('click', '.delete-file', function() {
  handleClear($(this));
  console.log('ok');
});

// function _(element)
// {
//     return document.getElementById(element);
// }

// _('start-upload-file-btn').onclick = function(event){
//     let patient_id = $('#upload_patient_id').val();

//     showLoader();
//     var form_data = new FormData();

//     var image_number = 1;

//     var error = '';

//     for(var count = 0; count < _('select_file').files.length; count++)
//     {

//         form_data.append("images[]", _('select_file').files[count]);
//         image_number++;
//     }

//     if(error != '')
//     {
//         _('uploaded_image').innerHTML = error;

//         _('select_file').value = '';
//     }
//     else
//     {
//         _('progress_bar').style.display = 'block';

//         var ajax_request = new XMLHttpRequest();

//         ajax_request.open("POST", "/patient/upload");

//         ajax_request.upload.addEventListener('progress', function(event){

//             var percent_completed = Math.round((event.loaded / event.total) * 100);

//             _('progress_bar_process').style.width = percent_completed + '%';

//             _('progress_bar_process').innerHTML = percent_completed + '%';

//         });

//         ajax_request.addEventListener('load', function(event){
//             location.reload();
//         });
//         ajax_request.addEventListener('error', function(event){
//             alert('Что-то пошло не так!');
//             console.log(event);
//         });
//         form_data.append(csrfParam,csrfToken);
//         form_data.append('file_title',$('#file_title').val());
//         form_data.append('file_description',$('#file_description').val());
//         form_data.append('patient_id',patient_id);
//         ajax_request.send(form_data);
//     }

// };


$('#start-upload-file-btn').click(function () {
  closeModal('.upload-files-modal');
  window.location.reload();
});