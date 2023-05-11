<div class="elemental-footnotes <% if $Style %> $StyleVariant<% end_if %> <% if $CSSClass %>$CSSClass<% end_if %>"">
    <% if $ShowTitle %>
    <h2 class="content-element__title">$Title</h2>
    <% end_if %>
    <% if $Notes %>
    <div class="footnotes-container">
        <% loop $Notes %>
        <div class="footnote-item">
            <% if $Top.Enumerate %>
            <span class="footnotes-index">$Pos</span>
            <% end_if %>
            <% if $Content %>
            <div class="footnotes-note">
                <div class="footnotes-content">$Content</div>
            </div>
            <% end_if %>
        </div>
        <% end_loop %>
    </div>
    <% end_if %>
</div>
