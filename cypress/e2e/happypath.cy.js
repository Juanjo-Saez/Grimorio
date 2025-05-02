describe('template spec', () => {
  it('Logs in successfully :)', () => {
    cy.visit('http://localhost');
    cy.get('input[type="email"]').type('pepeleches@gmail.com');
    cy.get('input[type="password"]').type('1234');
    cy.get('button').contains('Entrar').click()
    cy.contains('a', 'pepe-leches-1').should('be.visible')

    cy.get('a').contains('Nota').click()
    cy.get('input[type="text"]').type('Primera prueba')
    cy.get('textarea').type('Esto es una prueba')
    cy.get('button').contains('Guardar').click()
    cy.contains('a', 'Primera prueba').should('be.visible')

    cy.get('a[data-test-id="edit-Primera prueba"]').contains('Editar').click()
    cy.get('input[type="text"]').clear()
    cy.get('input[type="text"]').type('Segunda prueba')
    cy.get('textarea').clear()
    cy.get('textarea').type('Esto ya no es una prueba')
    cy.get('button').contains('Actualizar').click()
    cy.contains('a', 'Segunda prueba').should('be.visible')
    cy.contains('p', 'Esto ya no es una prueba').should('be.visible')

    cy.get('button[data-test-id="delete-Segunda prueba"]').contains('Eliminar').click()
    cy.contains('a', 'Segunda prueba').should('not.exist')

    cy.get('button').contains('Salir').click()

    cy.url().should('eq', 'http://localhost/')
  })
})