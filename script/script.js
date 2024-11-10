function showToast(type, message) {
  const toastContainer = document.getElementById("toast-container");
  const toast = `<div class="flex items-center space-x-3 bg-white p-4 rounded-lg border border-gray-400">
                        <i class="fa-solid ${
                          type == "Success"
                            ? "fa-circle-check"
                            : "fa-circle-xmark"
                        } fa-lg ${
    type == "Success" ? "text-green-400" : "text-red-400"
  }"></i>
                        <div>
                            <p>${type}</p>
                            <p>${message}</p>
                        </div>
                        <button onclick="closeToast()"><i class="fa-solid fa-xmark"></i></button>
                    </div>`;

  toastContainer.innerHTML += toast;

  setTimeout(function () {
    toastContainer.innerHTML = "";
  }, 3000);
}

function closeToast() {
  document.getElementById("toast-container").innerHTML = "";
}
