<?php

namespace Intop\Article\Enums;

enum ArticleTypeEnum: string
{
    case TYPE_ARTICLE = 'article';
    case TYPE_URL = 'url';
    case TYPE_OUT_URL = 'out_url';
    case TYPE_ATTACHMENT = 'attachment';


    public static function options(): array
    {
        return [
            self::TYPE_ARTICLE->value => '文章',
            self::TYPE_URL->value => '内部链接',
            self::TYPE_OUT_URL->value => '外部链接',
            self::TYPE_ATTACHMENT->value => '附件',
        ];
    }

    public function text(): string
    {
        return match ($this) {
            self::TYPE_ARTICLE => '文章',
            self::TYPE_URL => '内部链接',
            self::TYPE_OUT_URL => '外部链接',
            self::TYPE_ATTACHMENT => '附件',
        };
    }
}
