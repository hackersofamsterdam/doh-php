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
