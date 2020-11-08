<?php


class StringBuilder
{
    const CELL_DELIMITER = '*|!!|*';
    const ROW_DELIMITER = "*|\n!!\n|*";

    protected function createLine(array $line) : string {
        return implode(static::CELL_DELIMITER, $line) . static::ROW_DELIMITER;
    }

    public function generate(array $input) : string {
        $data = '';
        foreach($input as $item) {
            $data .= $this->createLine($item);
        }
        return $data;
    }

    public function parse(string $cvs) : array {

        $data = [];
        $lines = explode(static::ROW_DELIMITER, $cvs);
        foreach($lines as $line) {
            $data[] = explode(static::CELL_DELIMITER, $line);
            if(!$data[count($data) - 1][0]) {
                unset($data[count($data) - 1]);
            }
        }

        return $data;
    }


}
