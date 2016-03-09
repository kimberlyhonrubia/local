###*
# Act as our Helpers for all our projects.
#
# @class Helpers
# @package Base Helper Class
# @author Kim Honrubia <dev.Kimberlyhonrubia@gmail.com>
###
class Helpers

    isDebug: true

    obj: {}

    constructor: () ->
        @obj.startSymbol    = '{['
        @obj.endSymbol      = ']}'

    log: (msg)->
        console.log msg if @isDebug
    ###*
    # Console Log in Chrome, when developing
    #
    # @param {string} msg
    # @return void
    ###
    xlog: () ->
        args = Array.prototype.slice.call arguments
        console.log.apply console, args
    warn: () ->
        args = Array.prototype.slice.call arguments
        console.warn.apply console, args
    error: () ->
        args = Array.prototype.slice.call arguments
        console.error.apply console, args


    switchLanguage: (lang) ->
        $('#form-switch-language').submit () ->
            $('#lang').val lang
            $('#hash').val window.location.hash
        $('#form-switch-language').submit()

    isEqual: ( a, b ) -> `a == b`

    isUndefined: ( obj ) -> obj?

    isEmpty: ( obj ) ->
        undef = undefined
        key = undefined
        i = undefined
        len = undefined
        emptyValues = [undef, null, false, 0, "", "0"]
        i = 0
        len = emptyValues.length

        return true if _.isNaN(obj)
        while i < len
          return true  if obj is emptyValues[i]
          i++
        if typeof obj is "object"
          for key of obj
            return false
          return true
        false

    isEmptyObj: (obj) ->
        # null and undefined are "empty"
        if obj is null
            return true

        for key of obj
            if hasOwnProperty.call(obj, key)
                return false

        if typeof obj is 'undefined'
            return true

        if obj.length > 0
            return false

        if obj.length == 0
            return true

        return true

    isSet: (object, string) ->
      if !object
        return false
      childs = string.split('.')
      if childs.length > 0
        !childs.some((item) ->
          if item of object
            object = object[item]
            false
          else
            true
        )
      else if string of object
        true
      else
        false

    contains: (str,containsOf) ->
        if str.indexOf(containsOf) > -1
            return true
        return false

    disabled: (el) ->
        $(el).attr 'disabled', 'disabled'

    enabled: (el) ->
        $(el).removeAttr 'disabled'

    show :(el) ->
        $(el).removeClass('hide').addClass('show')

    hide :(el) ->
        $(el).addClass('hide').removeClass('show')

    addClass: (el, className) ->
        $(el).addClass(className)

    removeClass: (el, className) ->
        $(el).removeClass(className)

    addToRmClass: (el,className,timeout) ->
        timeout = 4000 if timeout is undefined
        el.addClass(className)
        setTimeout () ->
            el.removeClass(className)
        ,timeout

    redirectTo: (url) ->
        ua = navigator.userAgent.toLowerCase()
        isIE = ua.indexOf('msie') != -1
        version = parseInt(ua.substr(4, 2), 10)
        # Internet Explorer 8 and lower
        if isIE and version < 9
            link = document.createElement('a')
            link.href = url
            document.body.appendChild link
            link.click()
        else
            window.location.href = url

    growlMsg: (message,ballonType,header) ->

        ballonType  = if ballonType then ballonType else 'error'
        header      = 'System Message' if header is undefined
        $.jGrowl message,
            theme: 'growl-'+ballonType,
            header: header


    ###*
    # Format File Size into Human Readable Content
    #
    # @param {integer} bytes
    # @param {integer} si
    # @return string
    ###
    formatBytes: (bytes, si) ->
        si = 1024
        thresh = if si then 1000 else 1024
        if bytes < thresh
            return bytes + ' B'
        units = if si then [
            'KB'
            'MB'
            'GB'
            'TB'
            'PB'
            'EB'
            'ZB'
            'YB'
        ]
        else [
            'KiB'
            'MiB'
            'GiB'
            'TiB'
            'PiB'
            'EiB'
            'ZiB'
            'YiB'
        ]

        u = -1
        loop
            bytes /= thresh
            ++u
            unless bytes >= thresh
                break
        bytes.toFixed(1)+' '+units[u]


    ###*
    # check if the string given contains the following suffix value
    # at the end..
    #
    # @param {string} str
    # @param {array} suffix
    # @return bool
    ###
    endsWith: (str, suffix) ->
        str.indexOf(suffix, str.length - suffix.length) isnt -1


    ###*
    # Check if a specific value
    # is exist in Array
    #
    # @param {array} array
    # @param {string} value
    # @return bool
    ###
    inArray: (value,array) ->
        i = 0
        l = -1 + array.length
        m = Math.floor((l + 1) / 2)
        while i <= m
            if array[i] is value
                return true
            else if array[l - i] is value
                return true
            i++
        return false

    ###*
    # Format Number like PHP
    #
    # @param {integer} number
    # @param {integer} dec
    # @param {string} dsep
    # @param {string} tsep
    # @return string
    ###
    numberFormat: (number, dec, dsep, tsep) ->
        if isNaN(number) or number == null
            return ''
        number = number.toFixed(~ ~dec)
        tsep = if typeof tsep == 'string' then tsep else ','
        parts = number.split('.')
        fnums = parts[0]
        decimals = if parts[1] then (dsep or '.') + parts[1] else ''
        fnums.replace(/(\d)(?=(?:\d{3})+$)/g, '$1' + tsep) + decimals


    ###*
    # validate if a filename is an image or not... depending on
    # the allowedFileTypes array list.
    #
    # @return bool
    ###
    validate:
        allowedFileTypes: ['.gif','.jpg','.jpeg','.tiff','.png']
        fileType: (filename) ->
            return (new RegExp('(' + @allowedFileTypes.join('|').replace(/\./g, '\\.') + ')$')).test(filename)

    ###*
    # You need underscore.js for this helper _.template()
    #
    # @param {string} id
    # @return bool
    ###
    template: (id)->
        template = $('#'+ id).html()
        return _.template(template)

    ###*
    # Initialize angular module
    #
    # @param {string} moduleName
    # @param {array} config
    # @return angular instance
    ###
    angularInit: (moduleName, config) ->
        x = @
        angular.module(moduleName, config).config ['$interpolateProvider', ($interpolateProvider) ->
            $interpolateProvider.startSymbol(x.obj.startSymbol).endSymbol(x.obj.endSymbol)
        ]

    ###*
    # Enable angular ngModule
    # Bootstrap ng-modules attributes so we can have
    # multiple ng-app in our page without instatiating ng-app
    # multiple times.
    #
    # @return angular bootstrap all modules manually
    ###
    ngModuleEnable: () ->
        x = @
        angular.element(document).ready ->
          x._initNgModule document
          return

    ###*
    # Initialize ng-module methods
    #
    # @param {objet} element
    # @return {object}
    ###
    _initNgModule: (element) ->
        append = (element) ->
          element and elements.push(element)
          return
        elements = [element]
        moduleElements = []
        modules = []
        names = [
          "ng:module"
          "ng-module"
          "x-ng-module"
          "data-ng-module"
          "ng:modules"
          "ng-modules"
          "x-ng-modules"
          "data-ng-modules"
        ]
        NG_MODULE_CLASS_REGEXP = /\sng[:\-]module[s](:\s*([\w\d_]+);?)?\s/
        i = 0

        while i < names.length
          name = names[i]
          names[i] = true
          append document.getElementById(name)
          name = name.replace(":", "\\:")
          if element.querySelectorAll
            elements2 = undefined
            elements2 = element.querySelectorAll("." + name)
            j = 0

            while j < elements2.length
              append elements2[j]
              j++
            elements2 = element.querySelectorAll("." + name + "\\:")
            j = 0

            while j < elements2.length
              append elements2[j]
              j++
            elements2 = element.querySelectorAll("[" + name + "]")
            j = 0

            while j < elements2.length
              append elements2[j]
              j++
          i++
        i = 0

        while i < elements.length
          element = elements[i]
          className = " " + element.className + " "
          match = NG_MODULE_CLASS_REGEXP.exec(className)
          if match
            moduleElements.push element
            modules.push (match[2] or "").replace(/\s+/g, ",")
          else
            if element.attributes
              for attrName of element.attributes
                continue  if attrName is "length"
                attr =
                  name: attrName
                  value: element.attributes[attrName].value

                if names[attr.name]
                  moduleElements.push element
                  modules.push attr.value
          i++
        i = 0

        while i < moduleElements.length
          moduleElement = moduleElements[i]
          unless typeof (modules[i]) is "undefined"
            module = modules[i].replace(RegExp(" ", "g"), "").split(",")
            angular.bootstrap moduleElement, module
          i++


    ###*
    # Set LocaStorage Item using key value pair
    #
    # @param {string} key
    # @param {array,object,string} val
    # @return void
    ###
    set: (key, val) ->
        localStorage.setItem key, JSON.stringify val

    ###*
    # Get the save item from localStorage by its key id
    #
    # @param {string} key
    # @return mixed
    ###
    get: (key) ->
        try
            JSON.parse localStorage.getItem key
        catch e
            return false


    ###*
    # Get All Local Storage Keys
    #
    # @param {string} key
    # @return mixed
    ###
    getKeys: (startsWith) ->
        keys = []
        for key, items of localStorage
            if _.isString(startsWith) or _.isNumber(startsWith)
                if key.indexOf(startsWith) >= 0
                    keys.push key
            else
                keys.push key

        return keys


    ###*
    # Server Sent Events Instance
    #
    # @param {string} url here
    # @return mixed
    ###
    sse: (url) ->
        return new EventSource url

    ###*
    # Remove LocaStorage Item using key value pair
    #
    # @param {string} key
    # @return void
    ###
    remove: (key) ->
        localStorage.removeItem key
        return


    ###*
    # Remove LocaStorage Item using key Starting With the Provided
    # String Prefix
    #
    # @param {string} startsWith
    # @return void
    ###
    removeBeginsWith: (startsWith) ->
      myLength = startsWith.length
      x = @
      Object.keys(localStorage).forEach (key) ->
        if key.substring(0, myLength) == startsWith
          x.remove key
        return
      return

    ###*
    # Delete all data from LocaStorage
    # Set key_pair that you want to delete
    #
    # @param {string} key
    # @return void
    ###
    clear: (key_pair) ->
        for key, value of localStorage

            if key_pair is undefined or key_pair is true
                @remove key

            else if key.indexOf(key_pair) >= 0
                @remove key

        return

    getQueryString: (name) ->
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]')
        regex = new RegExp('[\\?&]' + name + '=([^&#]*)')
        results = regex.exec(location.search)
        if results == null then '' else decodeURIComponent(results[1].replace(/\+/g, ' '))


    ###*
    # Generate Random Number
    #
    # @param {string} key
    # @return void
    ###
    randomKey: (str_count) ->

        str_count  = 6 if not str_count
        result     = ''
        randomChar = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"

        for i in [ 0...str_count ] by 1
            result += randomChar.charAt Math.floor Math.random() * randomChar.length

        return result

    ###*
    # Convert Number to Letter
    #
    # @param {int} number
    # @return void
    ###
    numberToLetter: (number) ->
        mod = number % 26
        pow = number / 26 | 0
        out = if mod then String.fromCharCode(64 + mod) else pow--
        if pow
            @numberToLetter(pow) + out
        else
            out

    ###*
    # Validate Credit Card Number
    #
    # @params (string)  number  Credit Card Number to be Validated
    # @return void
    ###
    validateCardNumber: (number) ->

        num = number.replace(/[^\d]/, '')
        str = ''
        i = num.length - 1
        while i >= 0
            str += if i & 1 then num[i] else (parseInt(num[i]) * 2).toString()
            --i
        sum = str.split('').reduce(((prev, current) ->
            prev + parseInt(current)
        ), 0)
        sum % 10 == 0

    ###*
    # Get File Extension Name of the current string
    #
    # @params (string)  name    File Name
    # @return string
    ###
    getFileExtension: (name) ->
        return name.split('.').pop().toLowerCase()

    ###*
    # Validate if Extension is Allowed or Not from the given data
    #
    # @params (string)  fileName    File Name with Extension
    # @params (array)   exts        Extension Lists, that you allow
    # @return string
    ###
    isAllowedExtension: (fileName,exts) ->
        return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName)


    ###*
    # Request from your server using AJAX,
    # its configurable and you need to supply
    # the options object
    #
    # @return mixed
    ###
    ajaxCall:
        options:
            url: '/'
            type:'GET'
            headers: { 'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8' },
            dataType: 'json'
            data: {}
            success: () ->
            error: () ->
            complete: () ->
        send: () ->
            jQuery.ajax @options.url,
                type: @options.type
                headers: @options.headers
                dataType: @options.dataType
                success: @options.success
                error: @options.error
                complete: @options.complete
    ###
    # Display Order Number
    # e.g. A5F8F70A?
    # @return
    ###
    itemNumber : ( str ) ->
        _.last str.split '.'


window._h = new Helpers()