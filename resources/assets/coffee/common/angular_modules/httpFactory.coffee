###*
# HTTP Factory
#
# @class HTTP Factory
# @package Angular Modules
# @author Kim Honrubia <dev.kimberlyhonrubia@gmail.com>
###

angular.module('HttpFactory',[]).factory "HttpFactory",
    ['$http',
    '$rootScope'
    '$q'
        ($http,
        $rootScope,
        $q) ->

            sendRequest : (DATA,URL,METHOD,Q_DEFER,isAJAX) ->
                METHOD = 'POST' if !_h.isUndefined METHOD

                if isAJAX is true
                    @$$additionalHTTPHeader()

                httpDataRequest =
                    method: METHOD
                    url: URL
                    headers: { 'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8' }
                    data: $.param(DATA)

                if Q_DEFER
                    return @$$qDeferRequest httpDataRequest, Q_DEFER

                if METHOD == 'GET'
                    return $http.get(URL)

                return $http httpDataRequest

            ###
            #   Authentication   = Site Authentication Token
            #   X-Requested-With = Set Http Request to AJAX FORMAT
            ###
            $$additionalHTTPHeader : ( ) ->
                $http.defaults.headers.common['Authentication']   = AuthKey || null
                $http.defaults.headers.common['X-Requested-With'] = "XMLHttpRequest"

                return

            $$qDeferRequest : ( httpDataRequest, Q_DEFER ) ->

                @qRequest = [] if angular.isUndefined @qRequest
                @qRequest.push Q_DEFER

                promise = $http angular.extend httpDataRequest, { timeout : Q_DEFER.promise }
                promise.finally ( () ->
                    @qRequest = _.filter @qRequest, (request) ->
                        request.url != httpDataRequest.url

                    return
                )
                return promise

            cancelRequest : () ->
                angular.forEach @qRequest, (canceller) ->
                    canceller.resolve()
                    return
                @qRequest = [] if angular.isUndefined @qRequest
]
