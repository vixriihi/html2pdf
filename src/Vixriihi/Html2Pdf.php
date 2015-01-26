<?php

namespace Vixriihi;


class Html2Pdf extends Command {

    protected $cmd = 'wkhtmltopdf';

    public function execute($html = null)
    {
        $descriptorSpec = array(
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
            2 => array("file", "/tmp/html2pdf-error.log", "a") // stderr is a file to write to
        );

        $cmd = sprintf('%s %s %s - %s',
                $this->cmd,
                $this->getFlags(),
                $this->getParams(),
                ($this->outputFile == null ? $this->outputFile : '-')
            );
        $pdf = null;

        $process = proc_open($cmd, $descriptorSpec, $pipes, $cwd, $env);
        if (is_resource($process)) {
            fwrite($pipes[0], $html);
            fclose($pipes[0]);

            $pdf = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            proc_close($process);
        }
        return $pdf;
    }
}