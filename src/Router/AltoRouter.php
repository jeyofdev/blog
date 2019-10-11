<?php

    namespace jeyofdev\php\blog\Router;


    use AltoRouter as GlobalAltoRouter;


    class AltoRouter extends GlobalAltoRouter
    {
        /**
         * Match a given Request Url against stored routes
         * @param string $requestUrl
         * @param string $requestMethod
         * @return array|boolean Array with route information on success, false on failure (no match).
         */
        public function match($requestUrl = null, $requestMethod = null) {

            $params = array();
            $match = false;

            // set Request Url if it isn't passed as parameter
            if($requestUrl === null) {
                if (isset($_SERVER['REQUEST_URI'])) {
                    if (substr($_SERVER['REQUEST_URI'], -1) === "/") {
                        $requestUrl = substr($_SERVER['REQUEST_URI'], 0, -1);
                    } else {
                        $requestUrl = $_SERVER['REQUEST_URI'];
                    }
                    $requestUrl .= "/";
                } else {
                    $requestUrl = "/";
                }
            }

            // strip base path from request url
            $requestUrl = substr($requestUrl, strlen($this->basePath));

            // Strip query string (?a=b) from Request Url
            if (($strpos = strpos($requestUrl, '?')) !== false) {
                $requestUrl = substr($requestUrl, 0, $strpos);
            }

            // set Request Method if it isn't passed as a parameter
            if($requestMethod === null) {
                $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
            }

            foreach($this->routes as $handler) {
                list($method, $_route, $target, $name) = $handler;

                $methods = explode('|', $method);
                $method_match = false;

                // Check if request method matches. If not, abandon early. (CHEAP)
                foreach($methods as $method) {
                    if (strcasecmp($requestMethod, $method) === 0) {
                        $method_match = true;
                        break;
                    }
                }

                // Method did not match, continue to next route.
                if(!$method_match) continue;

                // Check for a wildcard (matches all)
                if ($_route === '*') {
                    $match = true;
                } elseif (isset($_route[0]) && $_route[0] === '@') {
                    $pattern = '`' . substr($_route, 1) . '`u';
                    $match = preg_match($pattern, $requestUrl, $params);
                } else {
                    $route = null;
                    $regex = false;
                    $j = 0;
                    $n = isset($_route[0]) ? $_route[0] : null;
                    $i = 0;

                    // Find the longest non-regex substring and match it against the URI
                    while (true) {
                        if (!isset($_route[$i])) {
                            break;
                        } elseif (false === $regex) {
                            $c = $n;
                            $regex = $c === '[' || $c === '(' || $c === '.';
                            if (false === $regex && false !== isset($_route[$i+1])) {
                                $n = $_route[$i + 1];
                                $regex = $n === '?' || $n === '+' || $n === '*' || $n === '{';
                            }
                            if (false === $regex && $c !== '/' && (!isset($requestUrl[$j]) || $c !== $requestUrl[$j])) {
                                continue 2;
                            }
                            $j++;
                        }
                        $route .= $_route[$i++];
                    }

                    $regex = $this->compileRoute($route);
                    $match = preg_match($regex, $requestUrl, $params);
                }

                if(($match == true || $match > 0)) {

                    if($params) {
                        foreach($params as $key => $value) {
                            if(is_numeric($key)) unset($params[$key]);
                        }
                    }

                    return array(
                        'target' => $target,
                        'params' => $params,
                        'name' => $name
                    );
                }
            }
            return false;
        }



        /**
         * Compile the regex for a given route (EXPENSIVE)
         */
        private function compileRoute($route) {
            if (preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', $route, $matches, PREG_SET_ORDER)) {

                $matchTypes = $this->matchTypes;
                foreach($matches as $match) {
                    list($block, $pre, $type, $param, $optional) = $match;

                    if (isset($matchTypes[$type])) {
                        $type = $matchTypes[$type];
                    }
                    if ($pre === '.') {
                        $pre = '\.';
                    }

                    //Older versions of PCRE require the 'P' in (?P<named>)
                    $pattern = '(?:'
                            . ($pre !== '' ? $pre : null)
                            . '('
                            . ($param !== '' ? "?P<$param>" : null)
                            . $type
                            . '))'
                            . ($optional !== '' ? '?' : null);

                    $route = str_replace($block, $pattern, $route);
                }

            }
            return "`^$route$`u";
        }
    }

