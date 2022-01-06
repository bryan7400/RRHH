// ============================================================== 
// cropperjs
// ============================================================== 
// Options
var options = {
    aspectRatio: 16 / 9,
    preview: '.image',
    ready: function (e) {
      console.log(e.type);
    },
    cropstart: function (e) {
      console.log(e.type, e.detail.action);
    },
    cropmove: function (e) {
      console.log(e.type, e.detail.action);
    },
    cropend: function (e) {
      console.log(e.type, e.detail.action);
    },
    crop: function (e) {
      var data = e.detail;

      console.log(e.type);
      dataX.value = Math.round(data.x);
      dataY.value = Math.round(data.y);
      dataHeight.value = Math.round(data.height);
      dataWidth.value = Math.round(data.width);
      dataRotate.value = typeof data.rotate !== 'undefined' ? data.rotate : '';
      dataScaleX.value = typeof data.scaleX !== 'undefined' ? data.scaleX : '';
      dataScaleY.value = typeof data.scaleY !== 'undefined' ? data.scaleY : '';
    },
    zoom: function (e) {
      console.log(e.type, e.detail.ratio);
    }
  };
actions.querySelector('.docs-toggles').onchange = function (event) {
    var e = event || window.event;
    var target = e.target || e.srcElement;
    var cropBoxData;
    var canvasData;
    var isCheckbox;
    var isRadio;

    if (!cropper) {
      return;
    }

    if (target.tagName.toLowerCase() === 'label') {
      target = target.querySelector('input');
    }

    isCheckbox = target.type === 'checkbox';
    isRadio = target.type === 'radio';

    if (isCheckbox || isRadio) {
      if (isCheckbox) {
        options[target.name] = target.checked;
        cropBoxData = cropper.getCropBoxData();
        canvasData = cropper.getCanvasData();

        options.ready = function () {
          console.log('ready');
          cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
        };
      } else {
        options[target.name] = target.value;
        options.ready = function () {
          console.log('ready');
        };
      }

      // Restart
      cropper.destroy();
      cropper = new Cropper(image, options);
    }
  };

// Methods
actions.querySelector('.docs-buttons').onclick = function (event) {
var e = event || window.event;
var target = e.target || e.srcElement;
var cropped;
var result;
var input;
var data;

if (!cropper) {
    return;
}

while (target !== this) {
    if (target.getAttribute('data-method')) {
    break;
    }

    target = target.parentNode;
}

if (target === this || target.disabled || target.className.indexOf('disabled') > -1) {
    return;
}

data = {
    method: target.getAttribute('data-method'),
    target: target.getAttribute('data-target'),
    option: target.getAttribute('data-option') || undefined,
    secondOption: target.getAttribute('data-second-option') || undefined
};

cropped = cropper.cropped;

if (data.method) {
    if (typeof data.target !== 'undefined') {
    input = document.querySelector(data.target);

    if (!target.hasAttribute('data-option') && data.target && input) {
        try {
        data.option = JSON.parse(input.value);
        } catch (e) {
        console.log(e.message);
        }
    }
    }

    switch (data.method) {
    case 'rotate':
        if (cropped && options.viewMode > 0) {
        cropper.clear();
        }

        break;

    case 'getCroppedCanvas':
        try {
        data.option = JSON.parse(data.option);
        } catch (e) {
        console.log(e.message);
        }

        if (uploadedImageType === 'image/jpeg') {
        if (!data.option) {
            data.option = {};
        }

        data.option.fillColor = '#fff';
        }

        break;
    }

    result = cropper[data.method](data.option, data.secondOption);

    switch (data.method) {
    case 'rotate':
        if (cropped && options.viewMode > 0) {
        cropper.crop();
        }

        break;

    case 'scaleX':
    case 'scaleY':
        target.setAttribute('data-option', -data.option);
        break;

    case 'getCroppedCanvas':
        if (result) {
        // Bootstrap's Modal
        $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

        if (!download.disabled) {
            download.download = uploadedImageName;
            download.href = result.toDataURL(uploadedImageType);
        }
        }

        break;

    case 'destroy':
        cropper = null;

        if (uploadedImageURL) {
        URL.revokeObjectURL(uploadedImageURL);
        uploadedImageURL = '';
        image.src = originalImageURL;
        }

        break;
    }

    if (typeof result === 'object' && result !== cropper && input) {
    try {
        input.value = JSON.stringify(result);
    } catch (e) {
        console.log(e.message);
    }
    }
}
};



// ============================================================== 
// final cropperjs
// ============================================================== 