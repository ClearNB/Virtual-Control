# Virtual Control
A Controlling Network Tool with using SNMP.

## 注意: 本パッケージはSNMP未実装です
実用的なツールとして用いらないよう、ご配慮をお願いいたします。
by Project GSC

## 1. 概要
Virtual Control は、ネットワーク監視に用いられるSNMPを管理・操作する、オープンソースのWebアプリケーションです。開発元はProject GSC（日本工学院北海道専門学校）。

## 2. 開発言語
PHP, HTML, CSS (SCSS 含む), JavaScript

## 3. 統合開発環境
NetBeans IDE 12.1

## 4. 動作確認OS
- Ubuntu 20.04 LTS
- Debian stretch
- CentOS 8.0

## 5. 必要サーバ
- Webサーバ (本システムを導入してください)
- SNMPエージェントサーバ
- データベースサーバ (Webサーバ内にある場合は不要)

## 6. 必須パッケージ
> Webサーバ
> - net-snmp
> - apache2
> - sfw (ファイアウォール系)

> SNMPエージェントサーバ
> - snmpd
> - snmptrapd
> - sfw (ファイアウォール系)

### 【注意】
- これらがデフォルトで導入されているものもあります
- 以上パッケージにおける動作確認は本システムで行いますが、導入操作は行いません
- 導入後の動作および設定が完全に行われていることを確認したうえでご利用ください
