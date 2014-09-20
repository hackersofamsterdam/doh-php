`var Request = function (options) {
    return Request.get(options);
};

Request.normalize = function (parameters) {
    return parameters ? (typeof parameters === "string" ? {url: parameters} : parameters) : false;
};

Request.json = function (parameters) {
    if (parameters = Request.normalize(parameters)) {
        parameters.json = true;

        return new Request.request(parameters);
    }
};

Request.get = function (options) {
    return new Request.request(options);
};

Request.post = function (parameters) {
    if (parameters = Request.normalize(parameters)) {
        parameters.method = "POST";

        return new Request.request(parameters);
    }
};

Request.put = function (parameters) {
    if (parameters = Request.normalize(parameters)) {
        parameters.method = "PUT";

        return new Request.request(parameters);
    }
};

Request.delete = function (parameters) {
    if (parameters = Request.normalize(parameters)) {
        parameters.method = "DELETE";

        return new Request.request(parameters);
    }
};

Request.request = function (options) {
    if (!options) {
        return false;
    }

    if (typeof options == "string") {
        options = {url: options};
    }

    if (options.method === "POST" || options.method === "PUT" || options.method == "DELETE") {
        var post = "?";

        for (var key in options.options) {
            post += options.options.hasOwnProperty(key) ? "&" + key + "=" + options.options[key] : "";
        }
    } else {
        options.method = "GET";
        options.url += options.url.indexOf("?") < 0 ? "?" : "";

        for (var key in options.options) {
            options.url += options.options.hasOwnProperty(key) ? "&" + key + "=" + options.options[key] : "";
        }
    }

    this.xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    this.xhr.options = options;
    this.xhr.callbacks = {
        then: [],
        error: []
    };

    this.then = function (callback) {
        this.xhr.callbacks.then.push(callback);

        return this;
    };

    this.error = function (callback) {
        this.xhr.callbacks.error.push(callback);

        return this;
    };

    this.xhr.call = function (categorie, result) {
        for (var i = 0; i < this.callbacks[categorie].length; i++) {
            if (typeof(this.callbacks[categorie][i]) === "function") {
                this.callbacks[categorie][i](result);
            }
        }
    };

    this.xhr.returnSuccess = function (result) {
        this.call("then", result);
    };

    this.xhr.returnError = function (message) {
        this.call("error", message);
    };

    this.xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status == 200) {
            var result = this.responseText;

            if (this.options.json) {
                try {
                    result = JSON.parse(result);
                } catch (error) {
                    this.returnError("invalid json");

                    return false;
                }
            }

            this.returnSuccess(result);
        } else if (this.readyState === 4 && this.status == 404) {
            this.returnError("404");
        } else if (this.readyState === 4) {
            this.returnError("unknow");
        }
    };

    this.xhr.open(options.method, options.url, true);
    this.xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    this.xhr.send(typeof post != "undefined" ? post : null);
}`

class window.iWant
  # In this array there will be the parameters set by the user. They should not be trusted as safe data
  configuration: {}

  # This is the API endpoint we're goint to be talking to
  @host: 'http://private-552a2-iwant.apiary-mock.com'

  # A collection with image ids from Wordpress
  imageIds: []

  # Class constructor
  constructor: (parameters) ->
    # Map the ['key', 'value'] arrays to key: value
    for parameter in parameters
      @configuration[parameter[0]] = parameter[1]

    # Get all image sources
    @getImageIds()

    @requestProductData()

  # Gets the resource's URL
  @getResourceURL: (resource, parameters = {}) =>
    base = @host + resource
    if parameters.length == 0
      base
    else
      qs = "?"
      for key, value of parameters
        qs += encodeURIComponent(key) + "=" + encodeURIComponent(value) + "&"

      qs = qs.substring 0, qs.length - 1

      base + qs

  # Function takes care of getting the images
  getImageIds: =>
    images = document.querySelectorAll('img[src]')

    # Loop through the image objects
    for image in images
      classParts[1] if classParts = image.className.match(/wp\-image\-(\d+)/)

  requestProductData: (callback) =>
    Request.json(
      iWant.getResourceURL('/products', {
        ids: @imageIds
      })
    ).then(callback)


window._iwant = new iWant _iwant

`Request('/index.html').then(function (){ console.log(true);});`


