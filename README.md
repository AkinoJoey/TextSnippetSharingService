# Text Snippetter
ユーザーがテキストやコードスニペットを共有できるWEBアプリケーションです。

## URL

## Demo

## 概要
Text Snippetterはユーザーアカウントを作成せずに、手軽にテキストやスニペットを共有できるWEBアプリケーションです。  
選択したプログラミング言語によって、シンタックスハイライトが適用されたテキストを簡単に共有できます。  
さらに、テキストの有効期限を設定でき、期限が過ぎたスニペットは自動的に削除されます。  

### 使い方
1. エディタに共有したいテキストを貼り付けます。
2. 「Syntax Highlight」のメニューから、使用している言語を選択します。
3. 「Text Expiration」のメニューから、スニペットの有効期限を選択します。
4. 「Create Snippet」ボタンをクリックすると、共有用の一意のURLに移動します。
5. このURLを通じて自分が作成したスニペットを他の人と共有します。

## 作成の経緯
以下の知識・技術を駆使して、動的なWEBアプリケーションを構築し、サーバーサイドのスキル向上を図りました。

- マイグレーションベースのスキーマ管理
- バックエンドからのデータベース操作
- SQLインジェクション対策
- サーバーサイドレンダリング
- 3層アーキテクチャ


## 使用技術
- フロントエンド
  - 使用言語： HTML, CSS, Javascript
  - コードエディタ: Monaco Editor
  - CSSフレームワーク: chota, Flexbox Grid

- バックエンド
  - 使用言語： PHP
  - データベース： MySQL
  - Webサーバー: NGINX
  - サーバー: Amazon EC2
  - SSL/TLS証明書更新: Certbot

## 期間
2023年11月10日から7日間かけて開発しました。

## こだわった点
### マイグレーションベースのスキーマ管理
データベーススキーマの管理は細かい変更にも対応できるように、マイグレーションベースの管理を採用しました。  
このために```code-gen```, ```migrate```という独自のコマンドを使用してスキーマ定義に関するファイルを生成し、マイグレーションの実行しています。  

以下はマイグレーションの手順の概要です。

1. ```php console migrate --init```コマンドを実行すると、マイグレーションのバージョン管理を行うためのmigrationsテーブルが生成されます。
2. ```php console code-gen migration --name {classname}```コマンドを実行すると、スキーマ定義を行うためのファイルが生成されます。このファイルは ```{YYYY-MM-DD}_{UNIX_TIMESTAMP}_{classname}.php```という命名規則に従います。
3. 作成されたファイルに、スキーマ定義を記述します。
4. ```php console migrate```コマンドを実行すると、スキーマ定義をしたファイルが実行されて、新しいスキーマがデータベースに反映されます。  

さらに、変更の取り消しも容易に行えるように、ロールバックの機能も実装しました。  
例えば```php console migrate --rollback 1```を実行すると、直前の1つのマイグレーションを取り消すことができます。  

### データのバリデーション
ユーザーから受け取るデータについては、厳格な検証とサニタイズを行った上で、データベースへの保存を行っています。  
具体的な例として、バックエンドに送信されるスニペットに対しては、以下の条件を検証するバリデーション関数が適用されます。  
これらの条件に一致する場合、データはデータベースに保存されず、クライアント側にアラートが表示されます。

- 空白のみの場合
- Unicode文字以外を使用している場合
- 文字数がデータベースのTEXT型の上限を越している場合

なお、INSERT INTOステートメントを実行する際には、プリペアドステートメントを使用しています。これにより、SQLインジェクションを防止し、セキュリティを確保しています。

### MVCアーキテクチャの実装
当プロジェクトでは、開発の拡張性と保守性向上を目指し、MVC（Model-View-Controller）アーキテクチャを導入しています。  
以下は、このアーキテクチャの具体的な実装についての説明です。  

#### モデル（Model）
データベースがモデルとして機能しています。これにより、データの永続性と取得が容易になります。

#### ビュー（View）
レンダリング用のインスタンス（HTTPRender）がビューを担当しています。この役割により、ユーザーに表示されるコンテンツを効果的に管理できます。

#### コントローラ（Controller）
ルーティングはコントローラの機能を果たしており、```Routing/routes.php```内のコールバックが担当しています。これにより、リクエストの受け入れと処理を制御しています。

リクエストの処理は以下の手順で行っています。

1. エントリーポイント（index.php）

受け取ったURLを解析し、そのパスがルーティングに存在するか確認します。

2. ルーティング

受け取った文字列に基づいて、対応するコールバック関数を返します。この関数内でデータベースからデータを取得し、それを利用してレンダリング用のインスタンスを生成します。

3. レンダリング

レンダリング用のインスタンスのメソッドを実行して、HTMLまたはJSONを生成します。
新しいパスを追加する際は、ルーティングに文字列とそれに対応するコールバック関数を追加するだけで良いため、柔軟で簡単な拡張が可能です。  
また、モデルとビューが完全に分離されているため、将来的な機能追加にも対応しやすく、高い保守性を確保しています。

## これからの改善点、拡張案
### 有効期限の詳細な設定
現在の実装では、スニペットの有効期限はシンプルな設計であり、10分後や1時間後などとなっています。しかしながら、より柔軟で使いやすいシステムにするために、日付や時刻など詳細な有効期限の設定ができると、利用者にとってより魅力的な機能になると考えています。

### ユーザーアカウントの作成
以下のような機能を追加するためにも、ユーザーアカウントの作成を検討しています。
- スニペットの自己管理
- 共有のメールアドレス制限
- パスワード保護