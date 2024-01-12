<?php

namespace App\Helpers;

class SimpleCSV
{
    private $_delimiter;
    private $_enclosure;
    private $_linebreak;
    private $_csv = '';

    public function __construct($delimiter = 'auto', $enclosure = 'auto', $linebreak = 'auto')
    {
        $this->_delimiter = $delimiter;
        $this->_enclosure = $enclosure;
        $this->_linebreak = $linebreak;
    }

    public static function import($filename_or_data, $is_data = false, $delimiter = 'auto', $enclosure = 'auto', $linebreak = 'auto')
    {
        $csv = new static($delimiter, $enclosure, $linebreak);

        return $csv->toArray($filename_or_data, $is_data);
    }

    public static function export($items, $delimiter = ',', $enclosure = '"', $linebreak = "\r\n")
    {
        $csv = new static($delimiter, $enclosure, $linebreak);

        return $csv->fromArray($items);
    }

    public function delimiter($set = false)
    {
        if (false !== $set) {
            return $this->_delimiter = $set;
        }
        if ('auto' === $this->_delimiter) {
            // detect delimiter
            if (false !== strpos($this->_csv, $this->_enclosure . ',')) {
                $this->_delimiter = ',';
            } elseif (false !== strpos($this->_csv, $this->_enclosure . "\t")) {
                $this->_delimiter = "\t";
            } elseif (false !== strpos($this->_csv, $this->_enclosure . ';')) {
                $this->_delimiter = ';';
            } elseif (false !== strpos($this->_csv, ',')) {
                $this->_delimiter = ',';
            } elseif (false !== strpos($this->_csv, "\t")) {
                $this->_delimiter = "\t";
            } elseif (false !== strpos($this->_csv, ';')) {
                $this->_delimiter = ';';
            } else {
                $this->_delimiter = ',';
            }
        }

        return $this->_delimiter;
    }

    public function enclosure($set = false)
    {
        if (false !== $set) {
            return $this->_enclosure = $set;
        }
        if ('auto' === $this->_enclosure) {
            // detect quot
            if (false !== strpos($this->_csv, '"')) {
                $this->_enclosure = '"';
            } elseif (false !== strpos($this->_csv, "'")) {
                $this->_enclosure = "'";
            } else {
                $this->_enclosure = '"';
            }
        }

        return $this->_enclosure;
    }

    public function linebreak($set = false)
    {
        if (false !== $set) {
            return $this->_linebreak = $set;
        }
        if ('auto' === $this->_linebreak) {
            if (false !== strpos($this->_csv, "\r\n")) {
                $this->_linebreak = "\r\n";
            } elseif (false !== strpos($this->_csv, "\n")) {
                $this->_linebreak = "\n";
            } elseif (false !== strpos($this->_csv, "\r")) {
                $this->_linebreak = "\r";
            } else {
                $this->_linebreak = "\r\n";
            }
        }

        return $this->_linebreak;
    }

    public function toArray($filename, $is_csv_content = false)
    {
        $this->_csv = $is_csv_content ? $filename : file_get_contents($filename);

        $CSV_LINEBREAK = $this->linebreak();
        $CSV_ENCLOSURE = $this->enclosure();
        $CSV_DELIMITER = $this->delimiter();

        $r = [];
        $cnt = strlen($this->_csv);

        $esc = $escesc = false;
        $i = $k = $n = 0;
        $r[$k][$n] = '';

        while ($i < $cnt) {
            $ch = $this->_csv[$i];
            $chch = ($i < $cnt - 1) ? $ch . $this->_csv[$i + 1] : $ch;

            if ($ch === $CSV_LINEBREAK) {
                if ($esc) {
                    $r[$k][$n] .= $ch;
                } else {
                    ++$k;
                    $n = 0;
                    $esc = $escesc = false;
                    $r[$k][$n] = '';
                }
            } elseif ($chch === $CSV_LINEBREAK) {
                if ($esc) {
                    $r[$k][$n] .= $chch;
                } else {
                    ++$k;
                    $n = 0;
                    $esc = $escesc = false;
                    $r[$k][$n] = '';
                }
                ++$i;
            } elseif ($ch === $CSV_DELIMITER) {
                if ($esc) {
                    $r[$k][$n] .= $ch;
                } else {
                    ++$n;
                    $r[$k][$n] = '';
                    $esc = $escesc = false;
                }
            } elseif ($chch === $CSV_ENCLOSURE . $CSV_ENCLOSURE && $esc) {
                $r[$k][$n] .= $CSV_ENCLOSURE;
                ++$i;
            } elseif ($ch === $CSV_ENCLOSURE) {
                $esc = !$esc;
            } else {
                $r[$k][$n] .= $ch;
            }
            ++$i;
        }

        return $r;
    }

    public function fromArray($items)
    {
        if (!is_array($items)) {
            trigger_error('CSV::export array required', E_USER_WARNING);

            return false;
        }

        $CSV_DELIMITER = $this->delimiter();
        $CSV_ENCLOSURE = $this->enclosure();
        $CSV_LINEBREAK = $this->linebreak();

        $result = '';
        foreach ($items as $i) {
            $line = '';

            foreach ($i as $v) {
                if (false !== strpos($v, $CSV_ENCLOSURE)) {
                    $v = str_replace($CSV_ENCLOSURE, $CSV_ENCLOSURE . $CSV_ENCLOSURE, $v);
                }

                if ((false !== strpos($v, $CSV_DELIMITER))
                    || (false !== strpos($v, $CSV_ENCLOSURE))
                    || (false !== strpos($v, $CSV_LINEBREAK))
                ) {
                    $v = $CSV_ENCLOSURE . $v . $CSV_ENCLOSURE;
                }
                $line .= $line ? $CSV_DELIMITER . $v : $v;
            }
            $result .= $result ? $CSV_LINEBREAK . $line : $line;
        }

        return $result;
    }
}
