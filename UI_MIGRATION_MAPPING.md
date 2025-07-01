# AdminLTE 2.3.2 → AdminLTE 4 + Bootstrap 5 移行マッピング

## 作成日: 2025年7月1日
## 目的: UI現代化における互換性マッピングとクラス変更一覧

---

## 1. AdminLTE レイアウト構造の変更

### コアレイアウトクラス
| AdminLTE 2.3.2 | AdminLTE 4 | 説明 |
|-----------------|-------------|------|
| `.main-header` | `.app-header` | ヘッダーコンテナ |
| `.main-sidebar` | `.app-sidebar` | サイドバーコンテナ |
| `.content-wrapper` | `.app-main` | メインコンテンツエリア |
| `.main-footer` | `.app-footer` | フッターコンテナ |
| `.sidebar-menu` | `.nav .nav-sidebar` | サイドバーナビゲーション |
| `.box` | `.card` | コンテンツボックス |
| `.box-header` | `.card-header` | ボックスヘッダー |
| `.box-body` | `.card-body` | ボックス本体 |
| `.box-footer` | `.card-footer` | ボックスフッター |

### サイドバーコンポーネント
| AdminLTE 2.3.2 | AdminLTE 4 | 説明 |
|-----------------|-------------|------|
| `.sidebar-toggle` | `.navbar-toggler` | サイドバー切り替えボタン |
| `.treeview` | `.nav-treeview` | ツリービューメニュー |
| `.treeview-menu` | `.nav .nav-treeview` | ツリービューサブメニュー |
| `.active` | `.active` | アクティブ状態（変更なし） |

---

## 2. Bootstrap 3.3.5 → Bootstrap 5.3 クラス変更

### グリッドシステム
| Bootstrap 3 | Bootstrap 5 | 説明 |
|-------------|-------------|------|
| `.col-xs-*` | `.col-*` | Extra smallグリッド |
| `.col-sm-*` | `.col-sm-*` | 変更なし |
| `.col-md-*` | `.col-md-*` | 変更なし |
| `.col-lg-*` | `.col-lg-*` | 変更なし |
| `.col-xs-offset-*` | `.offset-*` | Extra smallオフセット |
| `.col-sm-offset-*` | `.offset-sm-*` | Smallオフセット |

### 表示・非表示ユーティリティ
| Bootstrap 3 | Bootstrap 5 | 説明 |
|-------------|-------------|------|
| `.hidden-xs` | `.d-none .d-sm-block` | XSで非表示 |
| `.hidden-sm` | `.d-sm-none .d-md-block` | SMで非表示 |
| `.hidden-md` | `.d-md-none .d-lg-block` | MDで非表示 |
| `.hidden-lg` | `.d-lg-none .d-xl-block` | LGで非表示 |
| `.visible-xs` | `.d-block .d-sm-none` | XSでのみ表示 |
| `.visible-sm` | `.d-none .d-sm-block .d-md-none` | SMでのみ表示 |

### フロートユーティリティ
| Bootstrap 3 | Bootstrap 5 | 説明 |
|-------------|-------------|------|
| `.pull-left` | `.float-start` | 左フロート |
| `.pull-right` | `.float-end` | 右フロート |
| `.center-block` | `.mx-auto` | 中央配置 |

### テキストユーティリティ
| Bootstrap 3 | Bootstrap 5 | 説明 |
|-------------|-------------|------|
| `.text-left` | `.text-start` | 左寄せ |
| `.text-right` | `.text-end` | 右寄せ |
| `.text-center` | `.text-center` | 変更なし |

---

## 3. コンポーネント変更

### パネル → カード
| Bootstrap 3 | Bootstrap 5 | 説明 |
|-------------|-------------|------|
| `.panel` | `.card` | パネルコンテナ |
| `.panel-default` | `.card` | デフォルトパネル |
| `.panel-primary` | `.card .text-bg-primary` | プライマリパネル |
| `.panel-success` | `.card .text-bg-success` | 成功パネル |
| `.panel-info` | `.card .text-bg-info` | 情報パネル |
| `.panel-warning` | `.card .text-bg-warning` | 警告パネル |
| `.panel-danger` | `.card .text-bg-danger` | 危険パネル |
| `.panel-heading` | `.card-header` | パネルヘッダー |
| `.panel-body` | `.card-body` | パネル本体 |
| `.panel-footer` | `.card-footer` | パネルフッター |

### ボタンサイズ
| Bootstrap 3 | Bootstrap 5 | 説明 |
|-------------|-------------|------|
| `.btn-xs` | `.btn-sm` | 最小ボタン（削除済み） |
| `.btn-sm` | `.btn-sm` | 変更なし |
| `.btn` | `.btn` | 変更なし |
| `.btn-lg` | `.btn-lg` | 変更なし |

### Well → Card
| Bootstrap 3 | Bootstrap 5 | 説明 |
|-------------|-------------|------|
| `.well` | `.card .card-body` | ウェルコンテナ |
| `.well-sm` | `.card .card-body .p-2` | 小ウェル |
| `.well-lg` | `.card .card-body .p-4` | 大ウェル |

---

## 4. アイコンシステムの変更

### Glyphicons → Font Awesome 6 / Bootstrap Icons
| Glyphicons | Font Awesome 6 | Bootstrap Icons | 説明 |
|------------|-----------------|-----------------|------|
| `.glyphicon .glyphicon-user` | `.fas .fa-user` | `.bi .bi-person` | ユーザーアイコン |
| `.glyphicon .glyphicon-cog` | `.fas .fa-cog` | `.bi .bi-gear` | 設定アイコン |
| `.glyphicon .glyphicon-home` | `.fas .fa-home` | `.bi .bi-house` | ホームアイコン |
| `.glyphicon .glyphicon-search` | `.fas .fa-search` | `.bi .bi-search` | 検索アイコン |
| `.glyphicon .glyphicon-pencil` | `.fas .fa-pencil-alt` | `.bi .bi-pencil` | 編集アイコン |
| `.glyphicon .glyphicon-trash` | `.fas .fa-trash` | `.bi .bi-trash` | 削除アイコン |
| `.glyphicon .glyphicon-plus` | `.fas .fa-plus` | `.bi .bi-plus` | 追加アイコン |
| `.glyphicon .glyphicon-minus` | `.fas .fa-minus` | `.bi .bi-dash` | 削除アイコン |

---

## 5. フォームコンポーネント

### 入力グループ
| Bootstrap 3 | Bootstrap 5 | 説明 |
|-------------|-------------|------|
| `.input-group-addon` | `.input-group-text` | 入力グループアドオン |
| `.input-group-btn` | `.input-group` | 入力グループボタン |

### チェックボックス・ラジオ
| Bootstrap 3 | Bootstrap 5 | 説明 |
|-------------|-------------|------|
| `.checkbox` | `.form-check` | チェックボックスコンテナ |
| `.radio` | `.form-check` | ラジオボタンコンテナ |
| `.checkbox input` | `.form-check-input` | チェックボックス入力 |
| `.radio input` | `.form-check-input` | ラジオボタン入力 |
| `.checkbox label` | `.form-check-label` | チェックボックスラベル |
| `.radio label` | `.form-check-label` | ラジオボタンラベル |

---

## 6. JavaScript/jQuery 変更

### データ属性
| Bootstrap 3 | Bootstrap 5 | 説明 |
|-------------|-------------|------|
| `data-toggle` | `data-bs-toggle` | トグル機能 |
| `data-target` | `data-bs-target` | ターゲット指定 |
| `data-dismiss` | `data-bs-dismiss` | 閉じる機能 |
| `data-placement` | `data-bs-placement` | 配置指定 |

### jQuery プラグイン対応
| プラグイン | Bootstrap 5互換性 | 代替案 |
|-----------|------------------|-------|
| Select2 | 部分対応 | Choices.js, Tom Select |
| iCheck | 非対応 | CSS-only solutions |
| DatePicker | 部分対応 | Flatpickr, native inputs |
| Bootstrap Editable | 非対応 | Inline editing libraries |
| Nestable | 対応 | SortableJS |

---

## 7. AdminLTE 4 新機能

### ダークモード対応
```css
/* AdminLTE 4 新機能 */
.app-dark-mode {
  /* ダークモード専用スタイル */
}

/* CSS Custom Properties */
:root {
  --bs-primary: #007bff;
  --app-sidebar-bg: #343a40;
}
```

### 新しいコンポーネント
- `.app-brand` - ブランドロゴエリア
- `.nav-sidebar .nav-pill` - ピル型ナビゲーション
- `.card .card-outline` - アウトラインカード
- `.app-layout-fixed` - 固定レイアウト

---

## 8. 移行優先度

### 🔴 最高優先度（即座に対応必要）
- レイアウト構造クラス (`.main-*` → `.app-*`)
- グリッドシステム (`.col-xs-*` → `.col-*`)
- Glyphicons → Font Awesome/Bootstrap Icons

### 🟡 高優先度（早期対応推奨）
- パネル → カード変換
- 表示・非表示ユーティリティ
- データ属性の更新

### 🟢 中優先度（段階的対応可能）
- フロート → Flexbox
- ボタンサイズ調整
- jQuery プラグイン更新

---

## 9. 自動変換スクリプト対象

以下のクラスは正規表現で一括変換可能:

```bash
# レイアウト構造
sed -i 's/main-header/app-header/g' **/*.blade.php
sed -i 's/main-sidebar/app-sidebar/g' **/*.blade.php
sed -i 's/content-wrapper/app-main/g' **/*.blade.php

# Bootstrap グリッド
sed -i 's/col-xs-/col-/g' **/*.blade.php
sed -i 's/hidden-xs/d-none d-sm-block/g' **/*.blade.php
sed -i 's/pull-left/float-start/g' **/*.blade.php
sed -i 's/pull-right/float-end/g' **/*.blade.php

# データ属性
sed -i 's/data-toggle=/data-bs-toggle=/g' **/*.blade.php
sed -i 's/data-target=/data-bs-target=/g' **/*.blade.php
```

---

## 10. 手動対応必要項目

### Glyphicons → Font Awesome変換
各アイコンを個別に確認・変換が必要

### JavaScript イベントハンドラー
Bootstrap 5のJavaScript APIに合わせた調整が必要

### カスタムCSS
AdminLTE 2.3.2の上書きスタイルをAdminLTE 4用に調整

---

**作成者**: Claude Code Assistant  
**最終更新**: 2025年7月1日  
**ステータス**: フェーズ4.2.1完了