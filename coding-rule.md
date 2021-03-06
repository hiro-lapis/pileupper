コーディングに一貫性を持たせるために、
以下のルールのもとCSS設計を行うものとする。

1.命名規則について
BEMを採用。
ただしミクロなコンポーネントクラスでマーキングが十分な時は、構造的セマンティックな命名は不要とする。

ex.formタグ内のinputについて,
原則はc-input,c-form__inputは不要

c-form__inputは兄弟要素間の配置調整を行う時にのみ使用する

2.プロパティの詳細度(layout)

header main footerのサイズ、marginのみ定義
background-colorはプロジェクト固有のものなので
component(もしくはutility)でスタイルする

3.プロパティの詳細度(component)
メインとなるcontainer,form

メインを構成するミクロ（一つの機能を持つ）コンポーネントinput,img,title
がある。

1.で述べた通り、ミクロなコンポーネントの命名は必須。

その上でミクロなコンポーネントの配置を調整したり、
ひとまとめ(labelとinput,span(エラーメッセージ )などを一括り)にする必要がある時は追加でクラスを当てる。

formコンポーネントが他のページでも使用しうるのであればcomponentで管理する。


Q.マクロコンポーネントで使っていいプロパティは？

A.modifier(--ハイフン2つ)を付けて、
display,width,height,margin,padding,background,text-align
をスタイルしていい。
1つのブロックとしてコンセプトを定めるのに必要である。

ex.c-form--search{
    background: blue;
    border-style: none;
}

Q.マクロコンポーネントで控えた方がいいプロパティは？
A.elementでよく使うスタイルをマクロで定義しない。

ミクロコンポーネントでスタイルを上書きできるという点では問題ないが、
頻繁に適用するスタイルをマクロコンポーネントで当てると保守性が下がる。

Q.公式の「固有の色や幅を持つことは避ける」についての解釈は？

本プロジェクトでは色やサイズもcomponentで管理する。
個人開発程度の規模でコードも多くないのにprojectのファイルも使用するのは非効率的である。
命名（elementやmodifier）で区別がつくようにしつつも、

4.プロパティの詳細度(project)

「プロジェクト固有のスタイルで、各コンポーネントで使用頻度が高い」
ものをここに登録する。

プロジェクト固有のスタイルということは、逆にいえば他のプロジェクトでは使うことがないスタイルということである。

例えばヘッダーとフッターは同じテーマ（back-ground-color）が当たることが多いが、他のプロジェクトで使うとは考えにくい一方で、使用頻度はそれなりと考えられる。

theme-color.scss

というファイルを作ってそこで管理するのが妥当である。

そのプロダクト固有のデザインであるとか、カラーといった「」ものをここに登録する。



6.その他

Q.@exendは使用するか？
FLOCSSではマルチクラスを推奨する一方で、同じコンポーネント内であれば
許容するとしていて、どちらとも取れる。

A.@extendを使用する

Q.PCとスマホどちらからデザインするか？
A.スマホファースト

Q.IDタグは使う?
A.header,main,footer に使用。CSSは当てない。
あくまでセマンティックのために記述する
