<?php


namespace Krve\Inmobile;


class Response
{
    protected string $content;
    protected int $status;

    public function __construct(string $content, int $status)
    {
        $this->content = $content;
        $this->status = $status;
    }

    public function isOk(): bool
    {
        return $this->status >= 200 && $this->status < 300;
    }

    public function toArray(): array
    {
        $xml = simplexml_load_string(data: $this->content, options: LIBXML_NOCDATA);

        $data = [];

        foreach ($xml->recipient as $reply) {
            $item = [];

            foreach ($reply->attributes() as $key => $value) {
                $item[$key] = (string) $value;
            }

            $data[] = $item;
        }

        return $data;
    }
}
