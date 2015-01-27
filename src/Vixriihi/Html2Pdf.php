<?php

namespace Vixriihi;


class Html2Pdf extends Command {

    protected $cmd = 'wkhtmltopdf';
    protected $errorLog = '/tmp/html2pdf-error.log';

    protected $outputFile = null;

    /**
     * Converts the html to pdf
     *
     * @param $html
     * @return null|string
     */
    public function convert($html) {
        if (empty($html)) {
            return null;
        }
        try {
            $cmd = $this->__toString();
            //error_log($cmd);

            $descriptorSpec = array(
                0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
                1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
                2 => array("file", $this->errorLog, "a") // stderr is a file to write to
            );
            $process = proc_open($cmd, $descriptorSpec, $pipes);
            $pdf = null;
            if (is_resource($process)) {
                fwrite($pipes[0], $html);
                fclose($pipes[0]);

                $pdf = stream_get_contents($pipes[1]);
                fclose($pipes[1]);
                proc_close($process);
            }
            return $pdf;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Converts the parameters to the actual command line
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s%s %s - %s',
                $this->cmd,
                $this->getFlags(),
                $this->getParams(),
                ($this->outputFile !== null ? $this->outputFile : '-')
            );
    }

    /**
     * Sets the pdf output file
     *
     * @param $file
     */
    public function setPdfOutput($file) {
        $this->outputFile = $file;
    }

    /**
     * Sets the absolute location of wkhtmltopdf
     *
     * @param $cmd
     * @throws \Exception
     */
    public function setCmd($cmd) {
        if (!file_exists($cmd) || !is_executable($cmd)) {
            throw new \Exception(
                "Cannot set command to execute! File " .
                (file_exists($cmd) ? "is not executable" : "doesn't exist")
            );
        }
        $this->cmd = $cmd;
    }
}