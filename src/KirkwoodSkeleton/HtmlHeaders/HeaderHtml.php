<?php

namespace KirkwoodSkeleton\HtmlHeaders;

class HeaderHtml
{

    protected $metas = [];
    protected $scripts = [];
    protected $styles = [];
    protected $doctype = "HTML";
    protected $title;

    private $anonIndex = 0;

    private function addToArray(array &$internal, array $params, $name = "", array $requirements)
    {

        if ($name === "") {
            $name = "anon_" . $this->anonIndex;
            $this->anonIndex += 1;
        } else {
            $name = strtolower($name);
        }

        $internal[$name] = [
            "params" => $params,
            "requirements" => $requirements
        ];

    }

    private function removeFromArray(array &$internal, $name)
    {
        if (isset($internal[$name])) {
            unset($internal[$name]);
        }
    }

    public function removeMeta($name)
    {
        $this->removeFromArray($this->metas, $name);
    }

    public function removeScript($name)
    {
        $this->removeFromArray($this->scripts, $name);
    }

    public function removeStyle($name)
    {
        $this->removeFromArray($this->styles, $name);
    }

    private function prettyPrint($obj)
    {
        echo "<pre>" . var_export($obj, true) . "</pre>";
    }

    public function setDocType($doctype)
    {
        $this->doctype = $doctype;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function addMeta(array $params, $name = "", array $requirements = [])
    {
        $this->addToArray($this->metas, $params, $name, $requirements);
    }

    public function addScript($source, $name = "", array $requirements = [], array $additionParams = [])
    {
        $this->addToArray(
            $this->scripts,
            array_merge(["src" => $source], $additionParams),
            $name,
            $requirements
        );
    }

    public function addStyle($source, $name = "", array $requirements = [], array $additionalParams = [])
    {
        $this->addToArray(
            $this->styles,
            array_merge(["href" => $source, "rel" => "stylesheet"], $additionalParams),
            $name,
            $requirements
        );
    }

    private function getOrder(array &$internal)
    {
        $nameToInt = [];
        $intToName = [];
        $idx = 0;

        $adj = [];
        $jda = [];

        foreach ($internal as $name => $data) {
            $nameToInt[$name] = $idx;
            $idx += 1;
            $intToName[] = $name;
            $adj[] = [];
            $jda[] = [];
            $myNames[$name] = true;
        }

        foreach ($internal as $name => $data) {
            $node = $nameToInt[$name];
            $reqs = $data["requirements"] ? $data["requirements"] : [];

            foreach ($reqs as $string) {
                $pieces = explode(" ", $string);
                $directive = $pieces[0];
                $modules = array_map(function ($x) {
                    return strtolower($x);
                }, explode(",", $pieces[1]));

                if ($directive == "before") {
                    foreach ($modules as $module) {
                        if (isset($nameToInt[$module])) {
                            $modId = $nameToInt[$module];
                            $adj[$node][$modId] = true;
                            $jda[$modId][$node] = true;
                        }
                    }
                } elseif ($directive == "after") {
                    foreach ($modules as $module) {
                        if (isset($nameToInt[$module])) {
                            $modId = $nameToInt[$module];
                            $adj[$modId][$node] = true;
                            $jda[$node][$modId] = true;
                        }
                    }
                }
            }
        }

        $queue = new \SplQueue();

        $ret = [];
        for ($i = 0; $i < count($nameToInt); $i++) {
            if (count($jda[$i]) === 0) {
                $queue->enqueue($i);
            }
        }

        while (!$queue->isEmpty()) {
            $current = $queue->dequeue();

            $ret[] = $intToName[$current];
            foreach ($adj[$current] as $childId => $bool) {
                unset($jda[$childId][$current]);

                if (count($jda[$childId]) === 0) {
                    $queue->enqueue($childId);
                }
            }
        }

        if (count($ret) != count($intToName)) {
            throw new \Exception("Unable to create header - circular dependencies detected.");
        }

        return $ret;
    }

    private function getScriptOrder()
    {
        try {
            $ret = $this->getOrder($this->scripts);
            return $ret;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            die();
        }
    }

    private function getMetaOrder()
    {
        try {
            $ret = $this->getOrder($this->metas);
            return $ret;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            die();
        }
    }

    private function getStyleOrder()
    {
        try {
            $ret = $this->getOrder($this->styles);
            return $ret;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            die();
        }
    }

    public function draw()
    {
        echo "<!DOCTYPE {$this->doctype}><html><head>";

        if ($this->title !== "") {
            echo "<title>{$this->title}</title>";
        }

        foreach ($this->getMetaOrder() as $name) {
            echo "<meta ";
            foreach ($this->metas[$name]["params"] as $key => $value) {
                echo $key . "=\"" . $value . "\" ";
            }
            echo ">";
        }

        foreach ($this->getScriptOrder() as $name) {
            echo "<script ";
            foreach ($this->scripts[$name]["params"] as $key => $value) {
                echo $key . "=\"" . $value . "\" ";
            }
            echo "></script>";
        }

        foreach ($this->getStyleOrder() as $name) {
            echo "<link ";
            foreach ($this->styles[$name]["params"] as $key => $value) {
                echo "$key" . "=\"" . $value . "\" ";
            }
            echo ">";
        }


        echo "</head>";
    }

    public function addHeadElements(array $data)
    {
        foreach ($data as $datum) {
            $length = count($datum);
            switch ($datum[0]) {
                case "script":
                    $this->addScript(
                        $datum[1],
                        $length > 2 ? $datum[2] : "",
                        $length > 3 ? $datum[3] : [],
                        $length > 4 ? $datum[4] : []
                    );
                    break;
                case "style":
                    $this->addStyle(
                        $datum[1],
                        $length > 2 ? $datum[2] : "",
                        $length > 3 ? $datum[3] : [],
                        $length > 4 ? $datum[4] : []
                    );
                    break;
                case "meta":
                    $this->addMeta(
                        $datum[1],
                        $length > 2 ? $datum[2] : "",
                        $length > 3 ? $datum[3] : []
                    );
                    break;
            }
        }
    }

}